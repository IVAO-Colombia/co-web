<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Enums\Permission;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PilotSlotsReportController extends Controller
{
    public function __invoke(Event $event): StreamedResponse
    {
        Gate::authorize(Permission::VIEW_EVENTS);

        $slots = $event->pilotSlots()->reserved()->with('pilot')->orderBy('departs_at')->get();

        return response()->streamDownload(function () use ($event, $slots): void {
            $handle = fopen('php://output', 'w');

            abort_if($handle === false, 500, 'Failed to open output stream.');

            fputcsv($handle, ['Event', 'Callsign', 'Route', 'VID', 'EOBT', 'ETA']);

            foreach ($slots as $slot) {
                fputcsv($handle, [
                    $event->name,
                    $slot->airline_icao.$slot->flight_number,
                    "{$slot->origin} → {$slot->destination}",
                    $slot->pilot?->vid,
                    $slot->departs_at->format('H:i').' UTC',
                    $slot->arrives_at ? $slot->arrives_at->format('H:i').' UTC' : '',
                ]);
            }

            fclose($handle);
        }, Str::slug($event->name).'-pilot-reservations.csv', ['Content-Type' => 'text/csv']);
    }
}
