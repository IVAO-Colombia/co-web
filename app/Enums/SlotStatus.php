<?php

declare(strict_types=1);

namespace App\Enums;

enum SlotStatus: string
{
    case AVAILABLE = 'available';
    case RESERVED = 'reserved';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
}
