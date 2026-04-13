<?php

declare(strict_types=1);

namespace App\Enums;

enum EventStatus: string
{
    case DRAFT = 'draft';
    case ACTIVE = 'active';
    case CANCELLED = 'cancelled';
    case FINALIZED = 'finalized';
}
