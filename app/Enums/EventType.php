<?php

declare(strict_types=1);

namespace App\Enums;

enum EventType: string
{
    case ONLINE_DAY = 'online_day';
    case EXAM = 'exam';
    case TRAINING = 'training';
    case RFO = 'rfo';
    case RFE = 'rfe';
    case AIRBRIDGE = 'airbridge';
    case MSE = 'mse';
    case FLY_IN = 'fly_in';
    case FLY_OUT = 'fly_out';
    case FLY_IN_FLY_OUT = 'fly_in_fly_out';
}
