<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Event;

class EventSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $category = '';
    public $city = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $events = Event::upcoming()
            ->when($this->search, fn($q) => $q->search($this->search))
            ->when($this->category, fn($q) => $q->byCategory($this->category))
            ->when($this->city, fn($q) => $q->where('city', $this->city))
            ->with('organiser:id,name', 'ticketTypes')
            ->paginate(9);

        return view('livewire.event-search', compact('events'));
    }
}
