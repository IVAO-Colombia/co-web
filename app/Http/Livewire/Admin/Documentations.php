<?php

namespace App\Http\Livewire\Admin;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\Documentation;

class Documentations extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public $modal = false;

    public $document_id,
    $name,
    $description,
    $type,
    $url,
    $file,
    $fileroute,
    $editing = false;

    protected $rules = [
        "name" => "required",
        "type" => "required",
        "url" => "url|ends_with:pdf",
        // "file" => "required_if:url,null"
    ];

    public function render()
    {
        // $this->authorize("viewAny", Documentation::class);

        return view('livewire.admin.documentation.view', [
            "documents" => Documentation::all()
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
        // $this->validate();

        if($this->file != null){
            $this->fileroute = Storage::disk('documents')->put(null,$this->file);
        }


        $document = Documentation::updateOrCreate(
            ["id" => $this->document_id],
            [
                "name" => $this->name,
                "description" => $this->description,
                "type" => $this->type,
                "file" => $this->fileroute,
                "url" => $this->url,
            ]
        );

        session()->flash(
            "message",
            $this->document_id
                ? "¡Actualización exitosa!"
                : "¡Alta Exitosa!"
        );

        $this->closeModal();
        $this->resetExcept("search");
    }

    public function edit($id)
    {
        $this->resetExcept("search");
        $this->editing = true;

        $document = Documentation::findOrFail($id);

        $this->document_id = $id;
        $this->name = $document->name;
        $this->description = $document->description;
        $this->type = $document->type;
        $this->url = $document->url;
        $this->file = $document->file;

        $this->openModal();

    }
    public function delete($id)
    {
        Documentation::find($id)->delete();
        session()->flash("message", "Registro eliminado correctamente");
    }
}
