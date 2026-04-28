<?php

declare(strict_types=1);

namespace App\Http\Controllers\Landing;

use App\Actions\Events\CancelAtcSlot;
use App\Actions\Events\ReserveAtcSlot;
use App\Exceptions\AtcReauthRequiredException;
use App\Exceptions\AtcReservationNotAllowedException;
use App\Http\Controllers\Controller;
use App\Models\AtcSlot;
use App\Models\Event;
use App\Models\User;
use App\Services\Ivao\Ivao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AtcSlotReservationsController extends Controller
{
    public function store(Request $request, Event $event, AtcSlot $slot): RedirectResponse
    {
        abort_if($slot->event_id !== $event->id, 404);
        abort_if($slot->isReserved(), 409, 'This slot has already been reserved.');

        /** @var User */
        $user = $request->user();

        try {
            (new ReserveAtcSlot(app(Ivao::class)))->handle($user, $slot);
        } catch (AtcReauthRequiredException) {
            return redirect()->route('auth.redirect');
        } catch (AtcReservationNotAllowedException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }

        return back()
            ->with('success', __('Your ATC slot has been reserved successfully.'));
    }

    public function destroy(Request $request, Event $event, AtcSlot $slot): RedirectResponse
    {
        abort_if($slot->event_id !== $event->id, 404);
        abort_if(! $slot->isReserved(), 409, 'This slot is not reserved.');

        /** @var User */
        $user = $request->user();

        abort_if($slot->atc_id !== $user->id, 403);

        try {
            (new CancelAtcSlot(app(Ivao::class)))->handle($user, $slot);
        } catch (AtcReauthRequiredException) {
            return redirect()->route('auth.redirect');
        } catch (AtcReservationNotAllowedException $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }

        return redirect()
            ->route('home.events.show', $event)
            ->with('success', __('Your ATC slot reservation has been cancelled successfully.'));
    }
}
