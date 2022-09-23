<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

use App\Models\Slider;

class Sliders extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;
    use WithPagination;

    public $slider_id,
        $title,
        $description,
        $image,
        $btn_text,
        $btn_url,
        $order,
        $status = true,
        $imagename;

    public $editing = false;

    public $modal = false,
        $search = "",
        $sort_id = "id",
        $sort = "desc";

    protected $rules = [
        "title" => "required",
        "imagename" => "nullable|image",
    ];

    public function render()
    {
        $this->authorize("viewAny", App\Models\Slider::class);
        return view("livewire.admin.sliders.view", [
            "sliders" => Slider::orderBy($this->sort_id, $this->sort)->paginate(
                10
            ),
        ]);
    }

    public function create()
    {
        $this->reset();
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
        $this->slider_id = null;
        $this->slug = null;
        $this->title = null;
        $this->button_text = null;
        $this->image = null;
        $this->imagename = null;
        $this->description = null;
        $this->has_booking = false;
        $this->confirm_booking = false;
        $this->confirm_booking = false;
        $this->featured = false;
    }

    public function store()
    {
        $this->validate();

        if ($this->imagename) {
            $this->image = $this->imagename->store(null, "sliders");
        }

        Slider::updateOrCreate(
            ["id" => $this->slider_id],
            [
                "title" => $this->title,
                "image" => $this->image,
                "order" => $this->order,
                "btn_text" => $this->btn_text,
                "btn_url" => $this->btn_url,
                "description" => $this->description,
                "status" => $this->status,
            ]
        );

        session()->flash(
            "message",
            $this->slider_id ? "¡Actualización exitosa!" : "¡Alta Exitosa!"
        );

        $this->closeModal();
        $this->reset();
    }

    public function edit($id)
    {
        $this->reset();
        $this->editing = true;

        $slider = Slider::findOrFail($id);
        $this->slider_id = $id;
        $this->title = $slider->title;
        $this->order = $slider->order;
        $this->description = $slider->description;
        $this->image = $slider->image;
        $this->btn_text = $slider->btn_text;
        $this->btn_url = $slider->btn_url;
        $this->status = $slider->status;
        $this->openModal();
    }

    public function delete($id)
    {
        Slider::find($id)->delete();
        session()->flash("message", "Registro eliminado correctamente");
    }
}
