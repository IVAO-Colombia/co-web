<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Mail\{Sendcontact};
use App\Models\{FlightIvao, Slider, Airport, Event, Virtualairline};
use Mail;

class FrontController extends Controller
{
    function comingsoon()
    {
        return view("website.theme-1.comingsoon");
    }

    function index()
    {
        $flights = [];
        $flights = FlightIvao::flightsV2();

        $sliders = Slider::where("status", 1)
            ->orderBy("order")
            ->get();
        $events = Event::where("featured", true)
            ->orderBy("id")
            ->get();
        return view(
            "website.theme-1.index",
            compact("flights", "sliders", "events")
        );
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

    function sendcontact(Request $request)
    {
        Mail::to("edgardoalvarez100@gmail.com")->send(new Sendcontact());
        echo "Enviado";
    }
}
