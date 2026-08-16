<?php

declare(strict_types=1);

namespace App\Actions\Events;

use App\Actions\Auth\RefreshOAuthToken;
use App\Enums\SlotStatus;
use App\Exceptions\AtcReauthRequiredException;
use App\Exceptions\AtcReservationNotAllowedException;
use App\Models\AtcSlot;
use App\Models\User;
use App\Services\Ivao\Ivao;

class ReserveAtcSlot
{
    public function __construct(private readonly Ivao $ivao) {}

    /**
     * @throws AtcReauthRequiredException
     * @throws AtcReservationNotAllowedException
     */
    public function handle(User $user, AtcSlot $slot): void
    {
        $fraStartDate = $slot->starts_at->toISOString();
        throw_if(! $fraStartDate, AtcReservationNotAllowedException::class, 'Invalid slot start date.');

        $eligibilityResponse = $this->ivao->checkAtcReservationEligibility(
            $slot->callsign,
            $user->vid,
            $fraStartDate,
        );

        throw_if($eligibilityResponse->status() === 401, AtcReauthRequiredException::class);
        throw_if(
            $eligibilityResponse->status() === 403,
            AtcReservationNotAllowedException::class,
            $eligibilityResponse->json('message') ?? ''
        );

        $oauthToken = $user->oauthToken;
        throw_if($oauthToken === null, AtcReauthRequiredException::class);

        if ($oauthToken->isExpired()) {
            $oauthToken = (new RefreshOAuthToken($this->ivao))->handle($oauthToken);
        }

        $bookingResponse = $this->ivao->createAtcBookingAsUser(
            accessToken: $oauthToken->access_token,
            atcPosition: $slot->callsign,
            startDate: $slot->starts_at->toIso8601String(),
            endDate: $slot->ends_at->toIso8601String(),
        );

        throw_if($bookingResponse->status() === 401, AtcReauthRequiredException::class);
        throw_if(
            $bookingResponse->status() === 400 || $bookingResponse->status() === 403,
            AtcReservationNotAllowedException::class,
            $bookingResponse->json('message') ?? ''
        );

        $slot->update([
            'status' => SlotStatus::CONFIRMED,
            'atc_id' => $user->id,
            'ivao_booking' => $bookingResponse->json(),
        ]);
    }
}
