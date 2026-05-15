<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Traits\ApiResponseTrait;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;

class EventApiController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $query = Event::upcoming()->with('organiser', 'ticketTypes');

        if ($request->has('category')) {
            $query->byCategory($request->category);
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        $events = $query->paginate(12);
        
        return $this->paginatedResponse($events, 'Events retrieved successfully.');
    }

    public function upcoming()
    {
        $events = Event::upcoming()->take(10)->get();
        return $this->successResponse(EventResource::collection($events), 'Upcoming events retrieved.');
    }

    public function show(Event $event)
    {
        $event->load('ticketTypes', 'organiser');
        return $this->successResponse(new EventResource($event), 'Event details retrieved.');
    }

    public function availability(Event $event)
    {
        $availability = $event->ticketTypes->map(function($type) {
            $booked = $type->bookings()->where('bookings.status', 'confirmed')->sum('bookings.quantity');
            $available = max(0, $type->quantity - $booked);
            return [
                'id' => $type->id,
                'name' => $type->name,
                'price' => $type->price,
                'available_count' => $available,
                'is_sold_out' => $available <= 0
            ];
        });

        return $this->successResponse($availability, 'Ticket availability retrieved.');
    }

    public function store(Request $request)
    {
        if (!$request->user()->tokenCan('event:write')) {
            return $this->errorResponse('Insufficient permissions: event:write ability required.', 403, ['error_code' => 'insufficient_permissions']);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'venue' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'category' => 'required|in:music,conference,culture,sports,education',
            'starts_at' => 'required|date|after:now',
            'ends_at' => 'nullable|date|after:starts_at',
            'total_capacity' => 'required|integer|min:1',
        ]);

        $data['organiser_id'] = $request->user()->id;
        $data['status'] = 'pending';

        $event = Event::create($data);

        return $this->successResponse(new EventResource($event), 'Event created successfully and is pending approval.', 201);
    }
}
