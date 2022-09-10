<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event;

class Events extends Component
{
    public $events, $title, $slug, $date_text;
    public $modal = false;

    public function render()
    {
        $this->events = Event::all();
        return view("livewire.events.view");
    }

    public function crear()
    {
        $this->clearFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->modal = true;
    }

    public function closeModal()
    {
        $this->modal = false;
    }

    public function clearFields()
    {
    }
}
