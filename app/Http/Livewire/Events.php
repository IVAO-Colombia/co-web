<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Livewire\Component;
use App\Models\Event;

class Events extends Component
{
    use AuthorizesRequests;

    public $events,
        $title,
        $slug,
        $date_time,
        $image,
        $start_publish_date,
        $end_publish_date,
        $description,
        $has_booking = false,
        $confirm_booking = false,
        $featured = false;
    public $modal = false;

    public function render()
    {
        $this->events = Event::all();
        return view("livewire.events.view");
    }

    public function create()
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
        $this->slug = "";
        $this->title = "";
        $this->date_time = "";
        $this->image = "";
        $this->start_publish_date = "";
        $this->end_publish_date = "";
        $this->description = "";
        $this->has_booking = false;
        $this->confirm_booking = false;
        $this->featured = false;
    }
}
