<?php

declare(strict_types=1);

namespace App\Enums;

enum SlotStatus: string
{
    case AVAILABLE = 'available';
    case UNAVAILABLE = 'unavailable';
    case CANCELLED = 'cancelled';
}
