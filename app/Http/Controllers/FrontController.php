<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\FlightIvao;

class FrontController extends Controller
{
    function comingsoon(){
        return view("website.theme-1.comingsoon");
    }

    function index(){
        $flights = [];
        $flights = FlightIvao::flightsV2();
        return view("website.theme-1.index",compact('flights'));
    }
}
