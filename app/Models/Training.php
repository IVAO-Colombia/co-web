<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;
    protected $dates = ["date_training"];

    public function trainer()
    {
        return $this->belongsTo("App\Models\User", "trainer_id");
    }

    public function member()
    {
        return $this->belongsTo("App\Models\User", "user_id");
    }

    public static function ratingPilot()
    {
        $ratingPilot = [
            2 => "Basic Flight Student (FS1)",
            3 => "Flight Student (FS2)",
            4 => "Advanced Flight Student (FS3)	",
            5 => "Private Pilot (PP)",
            6 => "Senior Private Pilot (SPP)",
            7 => "Commercial Pilot (CP)",
            8 => "Airline Transport Pilot (ATP)",
            9 => "Senior Flight Instructor (SFI)",
            10 => "Chief Flight Instructor (CFI)",
        ];

        return $ratingPilot;
    }

    public static function ratingAtc()
    {
        $ratingATC = [
            2 => "ATC Applicant (AS1)",
            3 => "ATC Trainee (AS2)",
            4 => "Advanced ATC Trainee (AS3)",
            5 => "Aerodrome Controller (ADC)",
            6 => "Approach Controller (APC)",
            7 => "Centre Controller (ACC)",
            8 => "Senior Controller (SEC)	",
            9 => "Senior ATC Instructor (SAI)",
            10 => "Chief ATC Instructor (CAI)",
        ];
        return $ratingATC;
    }
}
