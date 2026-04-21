<?php

declare(strict_types=1);

namespace App\Enums;

enum PagesComponents: string
{
    case EVENTS_INDEX = 'events/Index';
    case EVENTS_CREATE = 'events/Create';
    case EVENTS_EDIT = 'events/Edit';
    case EVENTS_SHOW = 'events/Show';

    /** Frontend components for different event-related pages */
    case LANDING_HOME = 'Welcome';
    case LANGING_EVENTS = 'landing/Events';
}
