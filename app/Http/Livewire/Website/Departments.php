<?php

namespace App\Http\Livewire\Website;

use App\Models\Departments as ModelsDepartments;
use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Departments extends Component
{
    use WithFileUploads;

    public $edit = false;
    public $canEdit = false;

    public $department;

    public $description, $title, $banner, $hasEvents;

    public $bannerInput;

    public function mount($department_id)
    {
        // Ever team wm can edit
        $teamWebmaster = Team::find(10);

        // Search department page
        $this->department = ModelsDepartments::where(
            "department_id",
            $department_id
        )->firstOrFail();

        // See if the page is active
        if ($this->department->active == false) {
            return abort(404);
        }

        // CAN EDIT?
        $user = Auth::user();
        if ($user) {
            if (
                ($user->currentTeam == $this->department->team &&
                    $this->department->team->hasUser($user)) ||
                ($user->currentTeam == $teamWebmaster &&
                    $teamWebmaster->hasUser($user))
            ) {
                $this->canEdit = true;
            } else {
                $this->canEdit = false;
            }
        }
    }

    public function render()
    {
        return view("website.theme-1.departments.view")->extends(
            "website.theme-1.layout.theme-1"
        );
    }

    public function editDepartment()
    {
        // Assign model variables
        $this->description = $this->department->description;
        $this->title = $this->department->title;
        $this->banner = $this->department->banner;
        $this->hasEvents = $this->department->events;

        // Make a call to Front-end (JS)
        $this->emit("editModeEnabled");

        // Enable edit mode
        $this->edit = $this->canEdit;
    }

    public function store()
    {
        try {
            // Validate data
            $this->validate([
                "title" => "required|string|max:255",
                "description" => "required|string",
            ]);

            // Save in storage
            if ($this->bannerInput) {
                if ($this->banner) {
                    Storage::disk("departments")->delete($this->banner);
                }
                $this->banner = $this->bannerInput->store(null, "departments");
            }

            // Save information from mount method to DB
            $this->department->title = $this->title;
            $this->department->description = $this->description;
            $this->department->banner = $this->banner;
            $this->department->events = $this->hasEvents;

            $this->department->save();

            // Change edit mode and show success message
            $this->edit = false;
            session()->flash(
                "message",
                "¡Departamento actualizado exitosamente!"
            );
        } catch (\Throwable $th) {
            $errorNum = random_int(100, 999);
            session()->flash(
                "error",
                "Ocurrio un error al guardar... contacte a WM o intente más tarde, error: $errorNum"
            );
            Log::info("Error al actualizar departamento: $errorNum");
            Log::error($th);
        }
    }
}
