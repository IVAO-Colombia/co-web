<?php

declare(strict_types=1);

namespace App\Http\Controllers\Landing;

use App\Enums\PagesComponents;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Response;

class EventsController extends Controller
{
    public function index(): Response
    {
        return inertia(PagesComponents::LANDING_EVENTS->value, [
            'events' => Event::query()
                ->orderBy('starts_at')
                ->active()
                ->get(),
        ]);
    }

    public function show(Request $request, Event $event): Response
    {
        $isLoggedIn = $request->user() !== null;

        if ($isLoggedIn) {
            $event->load(['pilotSlots.pilot', 'atcSlots.atc']);
        } else {
            $event->load(['pilotSlots', 'atcSlots']);
            $event->pilotSlots->each->makeHidden(['status', 'pilot_id']);
            $event->atcSlots->each->makeHidden(['status', 'atc_id']);
        }

        return inertia(PagesComponents::LANDING_EVENTS_SHOW->value, [
            'event' => $event,
        ]);
    }
}
