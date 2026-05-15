<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TicketType;

class SeatCounter extends Component
{
    public $ticketTypeId;
    public $quantity = 1;

    public function render()
    {
        $type = TicketType::findOrFail($this->ticketTypeId);
        $booked = $type->bookings()->where('status', 'confirmed')->sum('quantity');
        $available = max(0, $type->quantity - $booked);
        $subtotal = $type->price * $this->quantity;

        return view('livewire.seat-counter', compact('type', 'available', 'subtotal'));
    }
}
