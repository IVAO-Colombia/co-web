<?php

declare(strict_types=1);

namespace App\Http\Controllers\Landing;

use App\Enums\PagesComponents;
use App\Http\Controllers\Controller;
use App\Models\AtcPositionFra;
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
        $event->load(['pilotSlots', 'atcSlots']);
        if (! $request->user()) {
            $event->pilotSlots->each->makeHidden(['status', 'pilot_id']);
            $event->atcSlots->each->makeHidden(['status', 'atc_id']);
        }

        $frasByCallsign = AtcPositionFra::query()
            ->forIcao($event->atcSlots->pluck('callsign')->unique()->toArray())
            ->forDate($event->starts_at)
            ->get()
            ->groupBy('atc_compose_position');

        return inertia(PagesComponents::LANDING_EVENTS_SHOW->value, [
            'event' => $event,
            'frasByCallsign' => $frasByCallsign,
        ]);
    }
}
