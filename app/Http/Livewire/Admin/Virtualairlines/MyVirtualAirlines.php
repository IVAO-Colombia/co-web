<?php

namespace App\Http\Livewire\Admin\Virtualairlines;

use Livewire\Component;
use App\Models\{User, Virtualairline};

class MyVirtualAirlines extends Component
{
    public $virtualines;

    public function render()
    {
        $this->virtualines = Virtualairline::whereIn(
            "code",
            explode(",", auth()->user()->va_member_ids)
        )->get();
        return view("livewire.admin.virtualairlines.my-virtual-airlines");
    }
}
