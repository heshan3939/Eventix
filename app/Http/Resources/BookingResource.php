<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'quantity' => $this->quantity,
            'total_price' => $this->total_price,
            'status' => $this->status,
            'event' => new EventResource($this->whenLoaded('ticketType.event')),
            'ticket_type' => new TicketTypeResource($this->whenLoaded('ticketType')),
            'user' => new UserResource($this->whenLoaded('user')),
            'created_at' => $this->created_at,
        ];
    }
}
