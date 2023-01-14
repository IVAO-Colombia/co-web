<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

use App\Models\Team;

class Teams extends Component
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
        $this->authorize("viewAny", App\Models\Team::class);
        return view("livewire.admin.teams.view", [
            "teams" => Team::orderBy($this->sort_id, $this->sort)->paginate(10),
        ]);
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

        $this->openModal();
    }

    public function delete($id)
    {
        Slider::find($id)->delete();
        session()->flash("message", "Registro eliminado correctamente");
    }
}
