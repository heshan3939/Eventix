<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Http\Requests\StoreEventRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EventController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $events = Event::where('status', 'published')
            ->where('starts_at', '>', now())
            ->with(['ticketTypes', 'organiser'])
            ->latest()
            ->paginate(12);
            
        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        // If not published, only the organiser or admin can view
        if ($event->status !== 'published' && (!auth()->check() || (!auth()->user()->isAdmin() && auth()->id() !== $event->organiser_id))) {
            abort(404);
        }

        $event->load('ticketTypes', 'organiser');
        return view('events.show', compact('event'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();
        $data['organiser_id'] = $request->user()->id;
        $data['status'] = 'pending';

        \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            $event = Event::create(\Illuminate\Support\Arr::except($data, ['tickets']));

            foreach ($data['tickets'] as $ticketData) {
                $event->ticketTypes()->create([
                    'name' => $ticketData['name'],
                    'price' => $ticketData['price'],
                    'quantity' => $ticketData['quantity'],
                    'max_per_booking' => 10 // Default
                ]);
            }
        });

        return redirect()->route('dashboard')->with('success', 'Event created and pending approval.');
    }

    public function edit(Event $event)
    {
        $this->authorize('update', $event);
        return view('events.edit', compact('event'));
    }

    public function update(StoreEventRequest $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $data = $request->validated();
        $data['status'] = 'pending'; // Requires re-approval
        
        $event->update(\Illuminate\Support\Arr::except($data, ['tickets']));

        return redirect()->route('dashboard')->with('success', 'Event updated and pending approval.');
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $event->delete();

        return redirect()->route('dashboard')->with('success', 'Event deleted successfully.');
    }

    public function bookings(Event $event)
    {
        $this->authorize('view', $event);
        
        $bookings = $event->bookings()
            ->with(['user', 'ticketType', 'transaction'])
            ->latest()
            ->paginate(20);
            
        return view('events.bookings', compact('event', 'bookings'));
    }

    public function manageTickets(Event $event)
    {
        $this->authorize('update', $event);
        $event->load('ticketTypes');
        return view('events.tickets', compact('event'));
    }

    public function updateTickets(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $data = $request->validate([
            'tickets' => ['required', 'array'],
            'tickets.*.id' => ['required', 'exists:ticket_types,id'],
            'tickets.*.quantity' => ['required', 'integer', 'min:0'],
        ]);

        // Validation: Total quantity cannot exceed event capacity
        $totalRequestedQuantity = collect($data['tickets'])->sum('quantity');
        
        // Find tickets not included in the request (if any)
        $includedIds = collect($data['tickets'])->pluck('id')->toArray();
        $otherTicketsQuantity = $event->ticketTypes()->whereNotIn('id', $includedIds)->sum('quantity');
        
        $grandTotal = $totalRequestedQuantity + $otherTicketsQuantity;
        if ($grandTotal > $event->total_capacity) {
            return back()->withErrors(['tickets' => "The total capacity of all ticket tiers ($grandTotal) cannot exceed the event's total capacity ({$event->total_capacity})."]);
        }

        foreach ($data['tickets'] as $ticketData) {
            $ticket = $event->ticketTypes()->find($ticketData['id']);
            if ($ticket) {
                // Ensure they don't decrease below current bookings
                $booked = $ticket->bookings()->where('status', 'confirmed')->sum('quantity');
                if ($ticketData['quantity'] < $booked) {
                    return back()->withErrors(['tickets.' . $ticketData['id'] . '.quantity' => "Cannot reduce quantity below confirmed bookings ($booked)."]);
                }
                $ticket->update(['quantity' => $ticketData['quantity']]);
            }
        }

        return back()->with('success', 'Ticket quantities updated successfully.');
    }

    public function addTicketType(Request $request, Event $event)
    {
        $this->authorize('update', $event);
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        // Validation: Total quantity cannot exceed event capacity
        $currentSum = $event->ticketTypes()->sum('quantity');
        if (($currentSum + $data['quantity']) > $event->total_capacity) {
            $remaining = max(0, $event->total_capacity - $currentSum);
            return back()->withErrors(['quantity' => "Adding this tier would exceed event capacity ({$event->total_capacity}). Remaining available capacity: $remaining."]);
        }

        $event->ticketTypes()->create([
            'name' => $data['name'],
            'price' => $data['price'],
            'quantity' => $data['quantity'],
            'max_per_booking' => 10
        ]);

        return back()->with('success', 'New ticket type added successfully.');
    }
}
