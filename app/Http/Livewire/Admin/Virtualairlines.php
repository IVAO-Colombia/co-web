<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

use App\Models\Virtualairline;

class Virtualairlines extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;
    use WithPagination;

    public $virtualairlines_id,
        $icao,
        $iata,
        $name,
        $code,
        $description,
        $website,
        $imagen,
        $status = true;
    public $editing = false;

    public $modal = false,
        $search = "",
        $sort_id = "id",
        $sort = "desc";

    protected $rules = [
        "name" => "required",
        "icao" => "required",
    ];

    public function render()
    {
        $this->authorize("viewAny", App\Models\Virtualairline::class);
        return view("livewire.admin.virtualairlines.view", [
            "virtualairlines" => Virtualairline::orderBy(
                $this->sort_id,
                $this->sort
            )->paginate(10),
        ]);
    }
}
