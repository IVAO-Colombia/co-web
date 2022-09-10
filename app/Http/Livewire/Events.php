<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event;

class Events extends Component
{
    public $events;

    public function render()
    {
        $this->events = Event::all();
        return view("livewire.events.view");
    }
}
