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
}
