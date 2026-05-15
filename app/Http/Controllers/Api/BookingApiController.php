<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TicketType;
use App\Models\Transaction;
use App\Traits\ApiResponseTrait;
use App\Http\Resources\BookingResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BookingApiController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $bookings = $request->user()->bookings()
            ->with(['ticketType.event', 'user', 'transaction'])
            ->latest()
            ->paginate(10);
            
        return $this->paginatedResponse($bookings, 'User bookings retrieved.');
    }

    public function store(Request $request)
    {
        if (!$request->user()->tokenCan('booking:write')) {
            return $this->errorResponse('Insufficient permissions: booking:write ability required.', 403, ['error_code' => 'insufficient_permissions']);
        }

        $request->validate([
            'ticket_type_id' => 'required|exists:ticket_types,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        try {
            DB::beginTransaction();

            $ticketType = TicketType::with('event')->lockForUpdate()->findOrFail($request->ticket_type_id);
            
            $alreadyBooked = $ticketType->bookings()->where('bookings.status', 'confirmed')->sum('bookings.quantity');
            $available = $ticketType->quantity - $alreadyBooked;

            if ($request->quantity > $available) {
                return $this->errorResponse('Not enough tickets available.', 422);
            }

            $total = $ticketType->price * $request->quantity;
            $platformFee = $total * 0.05;

            $booking = Booking::create([
                'user_id' => $request->user()->id,
                'ticket_type_id' => $ticketType->id,
                'quantity' => $request->quantity,
                'total_price' => $total,
                'reference' => 'EVX-' . strtoupper(Str::random(8)),
                'status' => 'confirmed'
            ]);

            Transaction::create([
                'booking_id' => $booking->id,
                'amount' => $total,
                'platform_fee' => $platformFee,
                'status' => 'paid'
            ]);

            DB::commit();

            $booking->load(['ticketType.event', 'user', 'transaction']);

            return $this->successResponse(new BookingResource($booking), 'Booking confirmed successfully.', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), 422);
        }
    }

    public function destroy(Booking $booking, Request $request)
    {
        if (!$request->user()->tokenCan('booking:write')) {
             return $this->errorResponse('Insufficient permissions: booking:write ability required.', 403, ['error_code' => 'insufficient_permissions']);
        }

        if ($booking->user_id !== $request->user()->id) {
            return $this->errorResponse('Unauthorised to cancel this booking.', 403);
        }

        if ($booking->ticketType->event->starts_at->isPast()) {
            return $this->errorResponse('Cannot cancel past events.', 422);
        }

        $booking->cancel();

        return $this->successResponse(null, 'Booking cancelled successfully.', 204);
    }
}
