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
        // whereRaw(
        //     "? between start_publish_date and DATE_ADD(end_publish_date, INTERVAL 1 DAY)",
        //     [Carbon::now()]
        // )
        $featuredEvents = Event::orderBy("featured", "DESC")
            ->orderBy("id", "DESC")
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

    public function event_detail(Request $request, Event $event)
    {
        return view("website.theme-1.event-detail", compact("event"));
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

    function sendcontact(Request $request)
    {
        Mail::to("edgardoalvarez100@gmail.com")->send(new Sendcontact());
        echo "Enviado";
    }
}
