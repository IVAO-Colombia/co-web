<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use App\Models\Airport;

class Airports extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $editing = false;

    public $modal = false,
        $search = "",
        $sort_id = "id",
        $sort = "desc";

    protected $rules = [
        "title" => "required",
    ];

    public function render()
    {
        return view("livewire.admin.airports.view", [
            "airports" => Airport::where(
                "icao",
                "like",
                "%" . $this->search . "%"
            )
                ->Orwhere("iata", "like", "%" . $this->search . "%")
                ->Orwhere("name", "like", "%" . $this->search . "%")
                ->orderBy($this->sort_id, $this->sort)
                ->paginate(50),
        ]);
    }

    public function order($field)
    {
        if ($this->sort_id == $field) {
            if ($this->sort == "desc") {
                $this->sort = "asc";
            } else {
                $this->sort = "desc";
            }
        } else {
            $this->sort_id = $field;
        }
    }
}
