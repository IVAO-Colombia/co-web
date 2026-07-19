<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Actions\Events\CreateEvent;
use App\Actions\Events\UpdateEvent;
use App\Enums\EventStatus;
use App\Enums\EventType;
use App\Enums\PagesComponents;
use App\Enums\Permission;
use App\Http\Controllers\Controller;
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
            ->whereNull('parent_event_id')
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

    public function create(): Response
    {
        Gate::authorize(Permission::CREATE_EVENTS);

        return inertia(PagesComponents::EVENTS_CREATE->value);
    }

    public function store(StoreEventRequest $request): RedirectResponse
    {
        app(CreateEvent::class)->handle($request);

        return to_route('dashboard.events.index');
    }

    public function show(Event $event): Response
    {
        Gate::authorize(Permission::VIEW_EVENTS);

        $event->load(['pilotSlots.pilot', 'atcSlots.atc']);

        if ($event->is_recurring) {
            $event->load(['occurrences' => fn ($query) => $query->orderBy('starts_at')]);
        }

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
            'hasReservedPilotSlots' => $event->pilotSlots->filter(fn ($slot): bool => $slot->isReserved())->isNotEmpty(),
            'hasReservedAtcSlots' => $event->atcSlots->filter(fn ($slot): bool => $slot->isReserved())->isNotEmpty(),
        ]);
    }

    public function update(UpdateEventRequest $request, Event $event): RedirectResponse
    {
        app(UpdateEvent::class)->handle($request, $event);

        return to_route('dashboard.events.show', $event);
    }

    public function destroy(Event $event): RedirectResponse
    {
        Gate::authorize(Permission::DELETE_EVENTS);

        // A recurring template is deleted together with its whole series. If any
        // occurrence holds a reservation the deletion is blocked, so we never orphan
        // a booked occurrence (child occurrences are hidden from the admin index).
        $events = $event->is_recurring
            ? $event->occurrences()->get()->push($event)
            : collect([$event]);

        if ($events->contains(fn (Event $item): bool => $this->hasReservedSlots($item))) {
            throw ValidationException::withMessages([
                'event' => __('This event cannot be deleted because it has reserved slots.'),
            ]);
        }

        $events->each(function (Event $item): void {
            $item->pilotSlots()->update(['deleted_at' => now()]);
            $item->atcSlots()->update(['deleted_at' => now()]);
            $item->delete();
        });

        return to_route('dashboard.events.index');
    }

    private function hasReservedSlots(Event $event): bool
    {
        if ($event->pilotSlots()->reserved()->exists()) {
            return true;
        }

        return (bool) $event->atcSlots()->reserved()->exists();
    }
}
