<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FlightIvao extends Model
{
    use HasFactory;

    public static function flightsV2($soloVuelos = false){


        $whazzupivao = Cache::remember('whazzupivao', 20, function () {
            $filecontents = file_get_contents('https://api.ivao.aero/v2/tracker/whazzup');
            return $filecontents;
        });

        $countryicao = "SK";
        $staffcountry = 'CO';

        $data = json_decode($whazzupivao);

        //  dd($data->clients->atcs);
        //  dd(substr($data->clients->observers[3]->callsign, 0,strlen($staffcountry)) == $staffcountry);

        if($soloVuelos){
            $departure = self::flightsOut($data,$countryicao);
            $arrival = self::flightsIn($data,$countryicao);
            $flight = array_merge($departure,$arrival);
            return $flight;
        }else{
            $departure = self::flightsOut($data,$countryicao);
            $arrival = self::flightsIn($data,$countryicao);
            $atcs = self::atcs($data,$countryicao);
            $staff = self::staff($data,$staffcountry);

            return array ("atc" => $atcs, "departure" => $departure, "arrival" => $arrival, "staff" => $staff);
        }



    }

    public static function fasttrack($callsign,$vuelos)
    {
      foreach ($vuelos as $key => $vuelo) {
         if($vuelo->callsign == $callsign){
            return $vuelo;
         }
      }
    }


    private static function flightsOut($data, $countryicao){
        $arrayDeparture = [];
        foreach ($data->clients->pilots as $key => $value) {
            if(substr($value->flightPlan->departureId, 0,strlen($countryicao)) == $countryicao){
                $arrayDeparture [] = $value;
            }
        }

        return $arrayDeparture;
    }

    private static function flightsIn($data, $countryicao){
        $arrayIn = [];
        foreach ($data->clients->pilots as $key => $value) {
            if(substr($value->flightPlan->arrivalId, 0,strlen($countryicao)) == $countryicao){
                $arrayIn [] = $value;
            }
        }

        return $arrayIn;
    }

    private static function atcs($data, $countryicao){
        $arrayatc = [];
        foreach ($data->clients->atcs as $key => $value) {
            if(substr($value->callsign, 0,strlen($countryicao)) == $countryicao){
                $arrayatc [] = $value;
            }
        }
        return $arrayatc;
    }

    private static function staff($data, $staffcountry){
        $arraystaff = [];
        foreach ($data->clients->observers as $key => $value) {
            if(substr($value->callsign, 0,strlen($staffcountry)) == $staffcountry){
                $arraystaff [] = $value;
            }
        }
        return $arraystaff;
    }
}