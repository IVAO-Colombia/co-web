<?php

namespace App\Http\Livewire;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Livewire\Component;
use App\Models\Event;

class Events extends Component
{
    use AuthorizesRequests;

    public $events,
        $event_id,
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
        $this->event_id = null;
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

    public function store()
    {
        Event::updateOrCreate(
            ["id" => $this->event_id],
            [
                "title" => $this->title,
                "slug" => $this->slug,
                "image" => $this->image,
                "date_time" => $this->date_time,
                "start_publish_date" => $this->start_publish_date,
                "end_publish_date" => $this->end_publish_date,
                "description" => $this->description,
                "has_booking" => $this->has_booking,
                "confirm_booking" => $this->confirm_booking,
                "featured" => $this->featured,
            ]
        );

        session()->flash(
            "message",
            $this->event_id ? "¡Actualización exitosa!" : "¡Alta Exitosa!"
        );

        $this->closeModal();
        $this->clearFields();
    }

    public function edit($id)
    {
        $this->clearFields();
        $event = Event::findOrFail($id);
        $this->event_id = $id;
        $this->title = $event->title;
        $this->slug = $event->slug;
        $this->image = $event->image;
        $this->date_time = $event->date_time;
        $this->start_publish_date = $event->start_publish_date;
        $this->end_publish_date = $event->end_publish_date;
        $this->description = $event->description;
        $this->has_booking = $event->has_booking;
        $this->confirm_booking = $event->confirm_booking;
        $this->featured = $event->featured;
        $this->openModal();
    }

    public function delete($id)
    {
        Event::find($id)->delete();
        session()->flash("message", "Registro eliminado correctamente");
    }
}
