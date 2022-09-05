<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{FlightIvao, Trackerva};

class Virtualairline extends Model
{
    use HasFactory;

    public static function tracking()
    {
        $solovuelos = true;
        $flights = FlightIvao::flightsV2($solovuelos);
        $virtualarlines = Virtualairline::where("status", 1)->get();
        foreach ($virtualarlines as $key1 => $va) {
            foreach ($flights as $key2 => $flight) {
                $tracker = Trackerva::where("sessionId", $flight->id)->first();
                if ($tracker) {
                } else {
                    $tracker = new Trackerva();
                }

                //validar
            }
        }
    }
}
