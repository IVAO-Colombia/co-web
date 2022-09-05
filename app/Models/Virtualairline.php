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
                $icaova = mb_substr($flight->callsign, 0, 3);

                if ($va == $icaova) {
                    Log::info("Vuelo:" . $flight->callsign . " " . $va);
                    $tracker = Trackerva::where(
                        "sessionId",
                        $flight->id
                    )->first();
                    if ($tracker) {
                        if (!$flight->lastTrack->onGround) {
                            $tracker->arrivalTime = \Carbon\Carbon::now();
                        }
                    } else {
                        $tracker = new Trackerva();
                        $tracker->callsign = $flight->callsign;
                        $tracker->userId = $flight->userId;
                        $tracker->departureId =
                            $flight->flightPlan->departureId;
                        $tracker->arrivalId = $flight->flightPlan->arrivalId;
                        $tracker->aircraftId = $flight->flightPlan->aircraftId;
                        $tracker->sessionId = $flight->id;
                        $tracker->stateAircraft = $flight->lastTrack->state;
                        $tracker->onGround = $flight->lastTrack->onGround;
                        $tracker->groundSpeed = $flight->groundSpeed;
                        $tracker->route = $flight->route;
                        $tracker->remarks = $flight->remarks;
                        if (!$flight->lastTrack->onGround) {
                            $tracker->departureTime = \Carbon\Carbon::now();
                        }

                        $tracker->onlineTime = $flight->time;
                        $tracker->save();
                    }
                }

                //validar
            }
        }
    }
}
