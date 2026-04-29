<?php

declare(strict_types=1);

namespace App\Enums;

enum PagesComponents: string
{
    case EVENTS_INDEX = 'dashboard/events/Index';
    case EVENTS_CREATE = 'dashboard/events/Create';
    case EVENTS_EDIT = 'dashboard/events/Edit';
    case EVENTS_SHOW = 'dashboard/events/Show';
    case DASHBOARD_RESERVATIONS = 'dashboard/Reservations';

    /** Frontend components for different event-related pages */
    case LANDING_HOME = 'Welcome';
    case LANDING_EVENTS = 'landing/events/Index';
    case LANDING_EVENTS_SHOW = 'landing/events/Show';
    case LANDING_ABOUT_US = 'landing/Aboutus';
}
