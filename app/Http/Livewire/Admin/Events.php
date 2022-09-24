<?php

namespace App\Http\Livewire\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

use Livewire\Component;
use App\Models\Event;

class Events extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;
    use WithPagination;

    public $event_id,
        $title,
        $slug,
        $date_time,
        $image,
        $imagename,
        $start_publish_date,
        // $end_publish_date,
        $description,
        $has_booking = false,
        $confirm_booking = false,
        $featured = false,
        $editing = false;

    public $search,
        $sort = "desc",
        $sort_id = "id";

    public $modal = false;

    protected $rules = [
        "title" => "required",
        "imagename" => "nullable|image",
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        $this->authorize("viewAny", Event::class);
        return view("livewire.admin.events.view", [
            "events" => Event::where("title", "like", "%" . $this->search . "%")
                ->orderBy($this->sort_id, $this->sort)
                ->paginate(10),
        ]);
    }

    public function create()
    {
        $this->resetExcept("search");
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

    public function store()
    {
        $this->validate();

        if ($this->imagename) {
            $this->image = $this->imagename->store(null, "events");
        }

        Event::updateOrCreate(
            ["id" => $this->event_id],
            [
                "title" => $this->title,
                "slug" => $this->slug,
                "image" => $this->image,
                "date_time" => $this->date_time,
                "start_publish_date" => $this->start_publish_date,
                // "end_publish_date" => $this->end_publish_date,
                "description" => $this->description,
                "has_booking" => $this->has_booking,
                "confirm_booking" => $this->confirm_booking,
                "featured" => $this->featured,
            ]
        );

        $this->editing = false;

        session()->flash(
            "message",
            $this->event_id ? "¡Actualización exitosa!" : "¡Alta Exitosa!"
        );

        $this->closeModal();
        $this->resetExcept("search");
    }

    public function edit($id)
    {
        $this->resetExcept("search");
        $this->editing = true;
        $event = Event::findOrFail($id);
        $this->event_id = $id;
        $this->title = $event->title;
        $this->slug = $event->slug;
        $this->image = $event->image;
        $this->date_time = $event->date_time;
        $this->start_publish_date = $event->start_publish_date;
        // $this->end_publish_date = $event->end_publish_date;
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
