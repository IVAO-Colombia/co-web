<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\PagesComponents;
use App\Models\Event;
use Inertia\Response;

class EventsListController extends Controller
{
    public function __invoke(): Response
    {
        return inertia(PagesComponents::LANDING_EVENTS->value, [
            'events' => Event::query()
                ->orderBy('starts_at')
                ->active()
                ->get(),
        ]);
    }
}
