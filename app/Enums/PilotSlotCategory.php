<?php

declare(strict_types=1);

namespace App\Enums;

enum PilotSlotCategory: string
{
    case DEPARTURE = 'departure';
    case ARRIVAL = 'arrival';
}
