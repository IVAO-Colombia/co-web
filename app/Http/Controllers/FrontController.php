<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Mail\{Sendcontact};
use App\Models\{FlightIvao, Slider};
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
        $sliders = Slider::where('status',1)->orderBy("order")->get();
        return view("website.theme-1.index", compact("flights","sliders"));
    }

    function sendcontact(Request $request)
    {
        Mail::to("edgardoalvarez100@gmail.com")->send(new Sendcontact());
        echo "Enviado";
    }
}
