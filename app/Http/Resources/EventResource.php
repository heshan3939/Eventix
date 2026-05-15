<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'venue' => $this->venue,
            'city' => $this->city,
            'category' => $this->category,
            'starts_at' => $this->starts_at,
            'ends_at' => $this->ends_at,
            'status' => $this->status,
            'organiser' => new UserResource($this->whenLoaded('organiser')),
            'ticket_types' => TicketTypeResource::collection($this->whenLoaded('ticketTypes')),
            'created_at' => $this->created_at,
        ];
    }
}
