<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use App\Http\Resources\TicketTypeResource;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class TicketTypeApiController extends Controller
{
    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ticketTypes = TicketType::paginate(15);
        return $this->paginatedResponse($ticketTypes, 'Ticket types retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'max_per_booking' => 'nullable|integer|min:1',
            'sale_ends_at' => 'nullable|date|after:now',
        ]);

        $ticketType = TicketType::create($data);

        return $this->successResponse(new TicketTypeResource($ticketType), 'Ticket type created successfully.', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TicketType $ticketType)
    {
        return $this->successResponse(new TicketTypeResource($ticketType), 'Ticket type details retrieved.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TicketType $ticketType)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'quantity' => 'sometimes|integer|min:1',
            'max_per_booking' => 'nullable|integer|min:1',
            'sale_ends_at' => 'nullable|date',
        ]);

        $ticketType->update($data);

        return $this->successResponse(new TicketTypeResource($ticketType), 'Ticket type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TicketType $ticketType)
    {
        if ($ticketType->bookings()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete this ticket tier because it has existing bookings. Please adjust the capacity instead.'
            ], 400);
        }

        $ticketType->delete();
        return $this->successResponse(null, 'Ticket type deleted successfully.');
    }
}
