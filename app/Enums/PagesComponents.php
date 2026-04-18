<?php

declare(strict_types=1);

namespace App\Enums;

enum PagesComponents: string
{
    case EVENTS_INDEX = 'events/Index';
    case EVENTS_CREATE = 'events/Create';
    case EVENTS_SHOW = 'events/Show';
}
