<?php

declare(strict_types=1);

namespace App\Actions\Events;

use App\Actions\Auth\RefreshOAuthToken;
use App\Exceptions\AtcReauthRequiredException;
use App\Exceptions\AtcReservationNotAllowedException;
use App\Models\AtcSlot;
use App\Models\User;
use App\Services\Ivao\Ivao;

class CancelAtcSlot
{
    public function __construct(private readonly Ivao $ivao) {}

    /**
     * @throws AtcReauthRequiredException
     * @throws AtcReservationNotAllowedException
     */
    public function handle(User $user, AtcSlot $slot): void
    {
        $oauthToken = $user->oauthToken;
        throw_if($oauthToken === null, AtcReauthRequiredException::class);

        if ($oauthToken->isExpired()) {
            $oauthToken = (new RefreshOAuthToken($this->ivao))->handle($oauthToken);
        }

        /** @var int|null $bookingId */
        $bookingId = $slot->ivao_booking['id'] ?? null;

        throw_if(
            $bookingId === null,
            AtcReservationNotAllowedException::class,
            'No booking ID found.'
        );

        $response = $this->ivao->deleteAtcBookingAsUser(
            accessToken: $oauthToken->access_token,
            bookingId: $bookingId,
        );

        throw_if($response->status() === 401, AtcReauthRequiredException::class);
        throw_if(
            $response->status() === 403,
            AtcReservationNotAllowedException::class,
            $response->json('message') ?? ''
        );

        // 404 means the booking no longer exists on IVAO — treat as already cancelled and proceed.
        $slot->cancel();
    }
}
