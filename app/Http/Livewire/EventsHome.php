<?php

namespace App\Http\Livewire;

use Livewire\Component;

class EventsHome extends Component
{
    public $events;
    public function render()
    {
        return view('livewire.events-home');
    }
}
