<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;

class EventBookingForm extends Component
{
    public $event;
    public $selectedTickets = []; // [ticket_type_id => quantity]

    public function mount(Event $event)
    {
        $this->event = $event;
        foreach ($event->ticketTypes as $type) {
            $this->selectedTickets[$type->id] = 0;
        }
    }

    public function increment($typeId)
    {
        $type = $this->event->ticketTypes->find($typeId);
        $booked = $type->bookings()->where('bookings.status', 'confirmed')->sum('bookings.quantity');
        $available = $type->quantity - $booked;

        if ($this->selectedTickets[$typeId] < min($available, $type->max_per_booking)) {
            $this->selectedTickets[$typeId]++;
        }
    }

    public function decrement($typeId)
    {
        if ($this->selectedTickets[$typeId] > 0) {
            $this->selectedTickets[$typeId]--;
        }
    }

    public function getSubtotalProperty()
    {
        $total = 0;
        foreach ($this->event->ticketTypes as $type) {
            $total += $type->price * ($this->selectedTickets[$type->id] ?? 0);
        }
        return $total;
    }

    public function getCountProperty()
    {
        return array_sum($this->selectedTickets);
    }

    public function render()
    {
        return view('livewire.event-booking-form');
    }
}
