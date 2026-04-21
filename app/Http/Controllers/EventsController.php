<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Events\CreateEvent;
use App\Actions\Events\UpdateEvent;
use App\Enums\EventStatus;
use App\Enums\EventType;
use App\Enums\PagesComponents;
use App\Enums\Permission;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Inertia\Response;

class EventsController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize(Permission::VIEW_EVENTS);

        $events = Event::query()
            ->when($request->string('query')->isNotEmpty(), function ($q) use ($request): void {
                $search = $request->string('query')->toString();
                $q->where(function ($q) use ($search): void {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('name_en', 'like', "%{$search}%")
                        ->orWhere('locations', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', EventStatus::from($request->string('status')->toString())))
            ->when($request->filled('type'), fn ($q) => $q->where('type', EventType::from($request->string('type')->toString())))
            ->latest('starts_at')
            ->paginate(15)
            ->withQueryString();

        return inertia(PagesComponents::EVENTS_INDEX->value, [
            'events' => $events,
            'filters' => $request->only(['query', 'status', 'type']),
        ]);
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        app(CreateEvent::class)->handle($request);

        return to_route('events.index');
    }

    public function show(Event $event): Response
    {
        Gate::authorize(Permission::VIEW_EVENTS);

        $event->load(['pilotSlots.pilot', 'atcSlots.atc']);

        return inertia(PagesComponents::EVENTS_SHOW->value, [
            'event' => $event,
        ]);
    }

    public function edit(Event $event): Response
    {
        Gate::authorize(Permission::UPDATE_EVENTS);

        $event->load(['pilotSlots', 'atcSlots']);

        return inertia(PagesComponents::EVENTS_EDIT->value, [
            'event' => $event,
            'hasReservedPilotSlots' => $event->pilotSlots->reject(fn ($slot): bool => $slot->isReserved())->isNotEmpty(),
            'hasReservedAtcSlots' => $event->atcSlots->reject(fn ($slot): bool => $slot->isReserved())->isNotEmpty(),
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        app(UpdateEvent::class)->handle($request, $event);

        return to_route('events.show', $event);
    }

    public function destroy(Event $event): RedirectResponse
    {
        Gate::authorize(Permission::DELETE_EVENTS);

        $hasReservedPilotSlot = $event->pilotSlots()->reserved()->exists();
        $hasReservedAtcSlot = $event->atcSlots()->reserved()->exists();

        if ($hasReservedPilotSlot || $hasReservedAtcSlot) {
            throw ValidationException::withMessages([
                'event' => __('This event cannot be deleted because it has reserved slots.'),
            ]);
        }

        $event->pilotSlots()->update(['deleted_at' => now()]);
        $event->atcSlots()->update(['deleted_at' => now()]);
        $event->delete();

        return to_route('events.index');
    }
}
