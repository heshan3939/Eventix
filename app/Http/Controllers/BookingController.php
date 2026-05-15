<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TicketType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookingController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $bookings = $request->user()->bookings()
            ->with(['ticketType.event', 'transaction'])
            ->latest()
            ->paginate(10);
            
        return view('bookings.index', compact('bookings'));
    }

    public function store(Request $request)
    {
        // Support both old single format and new multi format for backward compatibility
        $ticketsData = $request->has('tickets') 
            ? $request->input('tickets') 
            : [$request->input('ticket_type_id') => $request->input('quantity')];

        if (empty($ticketsData)) {
            return back()->with('error', 'Please select at least one ticket.');
        }

        if (!$request->user()->isCustomer()) {
            return back()->with('error', 'Only customers can purchase tickets. Organisers and Admins are restricted from buying passes.');
        }

        try {
            DB::beginTransaction();

            $bookingsCreated = [];

            foreach ($ticketsData as $ticketTypeId => $quantity) {
                if ($quantity <= 0) continue;

                $ticketType = TicketType::with('event')->lockForUpdate()->findOrFail($ticketTypeId);
                
                $alreadyBooked = $ticketType->bookings()->where('bookings.status', 'confirmed')->sum('bookings.quantity');
                $available = $ticketType->quantity - $alreadyBooked;

                if ($quantity > $available) {
                    throw new \Exception("Not enough tickets available for {$ticketType->name}.");
                }

                $total = $ticketType->price * $quantity;

                $booking = Booking::create([
                    'user_id' => $request->user()->id,
                    'ticket_type_id' => $ticketType->id,
                    'quantity' => $quantity,
                    'total_price' => $total,
                    'reference' => 'EVX-' . strtoupper(Str::random(8)),
                    'status' => 'pending'
                ]);

                $bookingsCreated[] = $booking->id;
            }

            if (empty($bookingsCreated)) {
                throw new \Exception('No valid tickets selected.');
            }

            DB::commit();
            
            return redirect()->route('bookings.checkout', ['ids' => $bookingsCreated]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function checkout(Request $request)
    {
        $ids = $request->query('ids');
        if (!$ids || !is_array($ids)) {
            return redirect()->route('dashboard')->with('error', 'Invalid checkout session.');
        }

        $bookings = Booking::whereIn('id', $ids)
            ->where('user_id', $request->user()->id)
            ->where('status', 'pending')
            ->with('ticketType.event')
            ->get();

        if ($bookings->isEmpty()) {
            return redirect()->route('bookings.index')->with('info', 'No pending bookings to pay for.');
        }

        $totalAmount = $bookings->sum('total_price');

        return view('bookings.checkout', compact('bookings', 'totalAmount'));
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'booking_ids' => ['required', 'array'],
            'booking_ids.*' => ['exists:bookings,id'],
            'card_holder' => ['required', 'string', 'max:255'],
            'card_number' => ['required', 'string', 'regex:/^[0-9 ]+$/'],
            'expiry' => ['required', 'string', 'regex:/^(0[1-9]|1[0-2])\/?([0-9]{2})$/'],
            'cvv' => ['required', 'string', 'digits_between:3,4'],
        ]);

        $bookings = Booking::whereIn('id', $request->booking_ids)
            ->where('user_id', $request->user()->id)
            ->where('status', 'pending')
            ->get();

        if ($bookings->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'Checkout session expired or already processed.');
        }

        try {
            DB::beginTransaction();

            foreach ($bookings as $booking) {
                // Real-world logic: Re-verify availability before finalizing payment
                $ticketType = $booking->ticketType;
                $alreadyBooked = $ticketType->bookings()->where('status', 'confirmed')->sum('quantity');
                $available = $ticketType->quantity - $alreadyBooked;

                if ($booking->quantity > $available) {
                    throw new \Exception("The experience \"{$ticketType->event->title}\" ({$ticketType->name}) has reached capacity while you were in checkout. We cannot process this booking.");
                }

                $booking->update(['status' => 'confirmed']);
                
                $platformFee = $booking->total_price * 0.05; // 5% platform fee
                
                Transaction::create([
                    'booking_id' => $booking->id,
                    'amount' => $booking->total_price,
                    'platform_fee' => $platformFee,
                    'status' => 'paid',
                    'payment_method' => 'card',
                    'payment_reference' => 'PAY-' . strtoupper(Str::random(12))
                ]);
            }

            DB::commit();

            return redirect()->route('bookings.index')->with('success', 'Payment successful! Your experience is confirmed.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('bookings.index')->with('error', $e->getMessage());
        }
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('cancel', $booking);

        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Booking is already cancelled.');
        }

        $event = $booking->ticketType->event;
        $now = now();

        // Real-world logic: Cancellations allowed only up to 48 hours before event
        if ($now->gt($event->starts_at->subHours(48))) {
            return back()->with('error', 'Cancellations are only permitted up to 48 hours before the event commencement.');
        }

        try {
            DB::transaction(function() use ($booking) {
                $booking->cancel();
            });
            return back()->with('success', 'Booking cancelled. Your refund has been initiated.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to process cancellation.');
        }
    }
}
