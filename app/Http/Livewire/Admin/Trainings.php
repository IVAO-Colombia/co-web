<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Training;

class Trainings extends Component
{
    public function render()
    {
        return view("livewire.admin.trainings.view", [
            "trainingcreated" => Training::where("status", 1)->paginate(10),
            "trainingassigned" => Training::where("status", 2)->paginate(10),
        ]);
    }
}
