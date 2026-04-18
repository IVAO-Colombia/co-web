<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Events\CreateEvent;
use App\Enums\EventStatus;
use App\Enums\EventType;
use App\Enums\PagesComponents;
use App\Enums\Permission;
use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
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

    // public function edit(string $id) {}

    // public function update(Request $request, string $id) {}

    // public function destroy(string $id) {}
}
