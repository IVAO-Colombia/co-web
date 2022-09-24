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

    public $icao,
        $iata,
        $name,
        $country,
        $latitude,
        $longitude,
        $airport_id,
        $municipality;

    public $modal = false,
        $search = "",
        $sort_id = "id",
        $sort = "desc";

    protected $rules = [
        "name" => "required",
        "longitude" => "required",
        "latitude" => "required",
        "country" => "required",
        "municipality" => "required",
    ];

    public function render()
    {
        $this->authorize("viewAny", App\Models\Airport::class);
        return view("livewire.admin.airports.view", [
            "airports" => Airport::where(
                "icao",
                "like",
                "%" . $this->search . "%"
            )
                ->Orwhere("iata", "like", "%" . $this->search . "%")
                ->Orwhere("name", "like", "%" . $this->search . "%")
                ->orderBy($this->sort_id, $this->sort)
                ->paginate(15),
        ]);
    }

    public function create()
    {
        $this->resetExcept("search");
        $this->openModal();
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

        Airport::updateOrCreate(
            ["id" => $this->airport_id],
            [
                "icao" => $this->icao,
                "iata" => $this->iata,
                "name" => $this->name,
                "country" => $this->country,
                "municipality" => $this->municipality,
                "latitude" => $this->latitude,
                "longitude" => $this->longitude,
            ]
        );

        session()->flash(
            "message",
            $this->airport_id ? "¡Actualización exitosa!" : "¡Alta Exitosa!"
        );

        $this->closeModal();
        $this->resetExcept("search");
    }

    public function edit($id)
    {
        $this->resetExcept("search");
        $this->editing = true;

        $airport = Airport::findOrFail($id);
        $this->airport_id = $id;
        $this->name = $airport->name;
        $this->icao = $airport->icao;
        $this->iata = $airport->iata;
        $this->country = $airport->country;
        $this->municipality = $airport->municipality;
        $this->latitude = $airport->latitude;
        $this->longitude = $airport->longitude;
        $this->openModal();
    }

    public function delete($id)
    {
        Airport::find($id)->delete();
        session()->flash("message", "Registro eliminado correctamente");
    }
}
