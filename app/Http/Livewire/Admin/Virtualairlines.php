<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use DB;

use App\Models\{Virtualairline, Trackerva};

class Virtualairlines extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;
    use WithPagination;

    public $virtualairline_id,
        $airline_tracker,
        $airline_tracker_name,
        $icao,
        $iata,
        $name,
        $code,
        $description,
        $website,
        $imagen,
        $imagename,
        $show_in_web = true,
        $status = true;
    public $editing = false;

    public $modal = false,
        $modalinfo = false,
        $search = "",
        $sort_id = "id",
        $sort = "desc";

    protected $rules = [
        "name" => "required",
        "icao" => "required",
        "iata" => "required",
        "website" => "required",
        "imagename" => "nullable|image",
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

    public function openModal()
    {
        $this->modal = true;
    }

    public function information(Virtualairline $virtualiarline)
    {
        $this->modalinfo = true;
        $this->airline_tracker_name = $virtualiarline->name;
        // \DB::enableQueryLog();
        $this->airline_tracker = Trackerva::selectRaw(
            "virtualairines_id, WEEK(created_at, 1) week, SUM(TIMESTAMPDIFF(SECOND, departureTime, arrivalTime )) as secondFlight"
        )
            ->whereBetween("created_at", [
                DB::raw("DATE_SUB(NOW(), INTERVAL 90 DAY)"),
                DB::raw("NOW()"),
            ])
            ->where("virtualairines_id", $virtualiarline->id)
            ->whereNotNull("departureTime")
            ->whereNotNull("arrivalTime")
            ->groupByRaw("week")
            ->orderByRaw("week desc")
            ->get();
        // dd(\DB::getQueryLog());
    }

    public function closeModalInfo()
    {
        $this->modalinfo = false;
        $this->reset();
    }

    public function closeModal()
    {
        $this->modal = false;
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

    public function create()
    {
        $this->resetExcept("search");
        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        if ($this->imagename) {
            if ($this->imagen) {
                Storage::disk("virtualairlines")->delete($this->imagen);
            }
            $this->imagen = $this->imagename->store(null, "virtualairlines");
        }

        Virtualairline::updateOrCreate(
            ["id" => $this->virtualairline_id],
            [
                "name" => $this->name,
                "icao" => $this->icao,
                "iata" => $this->iata,
                "website" => $this->website,
                "imagen" => $this->imagen,
                "code" => $this->code,
                "description" => $this->description,
                "show_in_web" => $this->show_in_web,
                "status" => $this->status,
            ]
        );

        session()->flash(
            "message",
            $this->virtualairline_id
                ? "¡Actualización exitosa!"
                : "¡Alta Exitosa!"
        );

        $this->closeModal();
        $this->resetExcept("search");
    }

    public function edit($id)
    {
        $this->reset();
        $this->editing = true;

        $virtualirline = Virtualairline::findOrFail($id);
        $this->virtualairline_id = $id;
        $this->name = $virtualirline->name;
        $this->icao = $virtualirline->icao;
        $this->iata = $virtualirline->iata;
        $this->description = $virtualirline->description;
        $this->imagen = $virtualirline->imagen;
        $this->website = $virtualirline->website;
        $this->code = $virtualirline->code;
        $this->show_in_web = $virtualirline->show_in_web;
        $this->status = $virtualirline->status;
        $this->openModal();
    }

    public function delete($id)
    {
        Virtualairline::find($id)->delete();
        session()->flash("message", "Registro eliminado correctamente");
    }
}
