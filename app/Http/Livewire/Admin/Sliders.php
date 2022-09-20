<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

use App\Models\Slider;

class Sliders extends Component
{
    public $modal = false;

    public function render()
    {
        return view("livewire.admin.sliders.view", [
            "sliders" => Slider::paginate(10),
        ]);
    }
}
