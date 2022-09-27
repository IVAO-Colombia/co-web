<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Mail\{Sendcontact};
use App\Models\{FlightIvao, Slider, Airport, Event, Virtualairline, Training};
use Mail;

class FrontController extends Controller
{
    function comingsoon()
    {
        return view("website.theme-1.comingsoon");
    }

    function index()
    {
        // whereRaw(
        //     "? between start_publish_date and DATE_ADD(end_publish_date, INTERVAL 1 DAY)",
        //     [Carbon::now()]
        // )
        $featuredEvents = Event::orderBy("featured", "DESC")
            ->orderBy("start_publish_date", "desc")
            ->limit(3)
            ->get();

        $flights = [];
        $flights = FlightIvao::flightsV2();

        $sliders = Slider::where("status", 1)
            ->orderBy("order")
            ->get();

        return view(
            "website.theme-1.index",
            compact("flights", "sliders", "featuredEvents")
        );
    }

    public function events()
    {
        $events = Event::orderBy("start_publish_date", "desc")->paginate(12);
        return view("website.theme-1.events", compact("events"));
    }

    public function eventscalendar()
    {
        $events = Event::orderBy("start_publish_date", "desc")->get();
        return view("website.theme-1.eventscalendar", compact("events"));
    }

    public function event_detail(Request $request, Event $event)
    {
        return view("website.theme-1.event-detail", compact("event"));
    }

    public function training()
    {
        $ratingATC = Training::ratingAtc();
        $ratingPilot = Training::ratingPilot();

        $nextRatingPilot = auth()->user()->ratingpilot + 1;
        if ($nextRatingPilot < 11) {
            $textratingpilot = $ratingPilot[$nextRatingPilot];
        }

        $nextRatingATC = auth()->user()->ratingatc + 1;
        if ($nextRatingATC < 11) {
            $textratingATC = $ratingATC[$nextRatingATC];
        }

        $trainings = Training::where("user_id", auth()->user()->id)
            ->orderBy("id", "DESC")
            ->paginate(15);

        $trainingAtcOpen = Training::where("user_id", auth()->user()->id)
            ->where("typetraining", "ATC")
            ->where("status", 1)
            ->OrWhere("status", 2)
            ->get();
        $trainingPilotOpen = Training::where("user_id", auth()->user()->id)
            ->where("typetraining", "PILOTO")
            ->where("status", 1)
            ->OrWhere("status", 2)
            ->get();

        return view(
            "website.theme-1.training",
            compact(
                "ratingPilot",
                "ratingATC",
                "textratingpilot",
                "textratingATC",
                "trainings",
                "trainingAtcOpen",
                "trainingPilotOpen"
            )
        );
    }

    public function trainingatc(Request $request)
    {
        $trainingATC = Training::where("typetraining", "ATC")
            ->where("status", 1)
            ->OrWhere("status", 2)
            ->where("user_id", auth()->user()->id)
            ->get();
        if (count($trainingATC) > 0) {
            return redirect()
                ->back()
                ->with("danger", "Ya tiene un entrenamiento para piloto");
        }
        $trainingATC = new Training();
        $trainingATC->user_id = auth()->user()->id;
        $trainingATC->typetraining = "ATC";
        $trainingATC->rating = auth()->user()->ratingatc + 1;
        $trainingATC->status = 1;
        $trainingATC->save();

        return redirect()
            ->back()
            ->with("success", "Entrenamiento solicitado exitosamente!");
    }

    public function trainingpilot(Request $request)
    {
        $trainingpiloto = Training::where("typetraining", "PILOTO")
            ->where("user_id", auth()->user()->id)
            ->where("status", 1)
            ->OrWhere("status", 2)
            ->get();
        if (count($trainingpiloto) > 0) {
            return redirect()
                ->back()
                ->with("danger", "Ya tiene un entrenamiento para piloto");
        }
        $trainingpiloto = new Training();
        $trainingpiloto->user_id = auth()->user()->id;
        $trainingpiloto->typetraining = "PILOTO";
        $trainingpiloto->rating = auth()->user()->ratingpilot + 1;
        $trainingpiloto->status = 1;
        $trainingpiloto->save();

        return redirect()
            ->back()
            ->with("success", "Entrenamiento solicitado exitosamente!");
    }

    public function fasttrack(Request $request, $callsign)
    {
        $flights = FlightIvao::flightsV2(true);
        $flight = FlightIvao::fasttrack($callsign, $flights);

        if ($flight == null) {
            abort(404);
        }

        $departureAirport = Airport::where(
            "icao",
            $flight->flightPlan->departureId
        )->first();
        $arrivalAirport = Airport::where(
            "icao",
            $flight->flightPlan->arrivalId
        )->first();
        //  dd($flight);
        return view(
            "website.theme-1.fasttrack",
            compact("flight", "departureAirport", "arrivalAirport")
        );
    }

    function about()
    {
        return view("website.theme-1.about");
    }

    function fra()
    {
        return view("website.theme-1.fra");
    }
    function virtualairlines()
    {
        $virtualairlines = Virtualairline::where("status", 1)
            ->orderBy("name")
            ->get();
        return view(
            "website.theme-1.virtualairlines",
            compact("virtualairlines")
        );
    }

    function sendcontact(Request $request)
    {
        Mail::to("edgardoalvarez100@gmail.com")->send(new Sendcontact());
        echo "Enviado";
    }
}
