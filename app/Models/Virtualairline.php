<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\{FlightIvao, Trackerva};
use Log;

class Virtualairline extends Model
{
    use HasFactory;

    protected $fillable = [
        "icao",
        "iata",
        "name",
        "code",
        "description",
        "website",
        "imagen",
        "status",
    ];

    public static function tracking()
    {
        $solovuelos = true;
        $flights = FlightIvao::flightsWorldV2();
        $virtualarlines = Virtualairline::where("status", 1)->get();
        foreach ($virtualarlines as $key1 => $va) {
            foreach ($flights as $key2 => $flight) {
                $icaova = mb_substr($flight->callsign, 0, 3);

                if ($va->icao == $icaova) {
                    // Log::info("Vuelo:" . $flight->callsign . " " . $va);
                    $tracker = Trackerva::where(
                        "sessionId",
                        $flight->id
                    )->first();

                    if (!$tracker) {
                        $tracker = new Trackerva();
                    }

                    $tracker->virtualairines_id = $va->id;
                    $tracker->callsign = $flight->callsign;
                    $tracker->userId = $flight->userId;
                    $tracker->departureId = $flight->flightPlan->departureId;
                    $tracker->arrivalId = $flight->flightPlan->arrivalId;
                    $tracker->aircraftId = $flight->flightPlan->aircraftId;
                    $tracker->sessionId = $flight->id;
                    $tracker->stateAircraft = optional(
                        $flight->lastTrack
                    )->state;

                    $tracker->groundSpeed = optional(
                        $flight->lastTrack
                    )->groundSpeed;
                    $tracker->route = $flight->flightPlan->route;
                    $tracker->remarks = $flight->flightPlan->remarks;

                    //si es el primer registro y no esta en tierra se registra como si estuviera en vuelo
                    if (
                        $tracker->onGround == null &&
                        !$flight->lastTrack->onGround
                    ) {
                        $tracker->departureTime = \Carbon\Carbon::now();
                    } elseif (
                        !$flight->lastTrack->onGround &&
                        $tracker->departureTime == null
                    ) {
                        //no esta en tierra y la hora de salida esta en null la agregamo
                        $tracker->departureTime = \Carbon\Carbon::now();
                    }
                    $tracker->onGround = $flight->lastTrack->onGround;

                    $tracker->onlineTime = $flight->time;

                    if (
                        $tracker->departureTime != null &&
                        !$flight->lastTrack->onGround
                    ) {
                        $tracker->arrivalTime = \Carbon\Carbon::now();
                    }

                    $tracker->save();
                }

                //validar
            }
        }
    }
}
