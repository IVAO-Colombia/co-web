<?php

declare(strict_types=1);

namespace App\Http\Controllers\Landing;

use App\Actions\Events\CancelPilotSlot;
use App\Actions\Events\ReservePilotSlot;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PilotSlot;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PilotSlotReservationsController extends Controller
{
    public function store(Request $request, Event $event, PilotSlot $slot): RedirectResponse
    {
        abort_if($slot->event_id !== $event->id, 404);
        abort_if($slot->isReserved(), 409, 'This slot has already been reserved.');

        /** @var User */
        $user = $request->user();

        (new ReservePilotSlot)->handle($user, $slot);

        return back()
            ->with('success', __('Your pilot slot has been reserved successfully.'));
    }

    public function destroy(Request $request, Event $event, PilotSlot $slot): RedirectResponse
    {
        abort_if($slot->event_id !== $event->id, 404);
        abort_if(! $slot->isReserved(), 409, 'This slot is not reserved.');

        /** @var User */
        $user = $request->user();

        abort_if($slot->pilot_id !== $user->id, 403);

        (new CancelPilotSlot)->handle($user, $slot);

        return redirect()
            ->route('home.events.show', $event)
            ->with('success', __('Your pilot slot reservation has been cancelled successfully.'));
    }
}
