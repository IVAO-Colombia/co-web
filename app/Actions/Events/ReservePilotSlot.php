<?php

declare(strict_types=1);

namespace App\Actions\Events;

use App\Enums\SlotStatus;
use App\Models\PilotSlot;
use App\Models\User;

class ReservePilotSlot
{
    public function handle(User $user, PilotSlot $slot): void
    {
        $slot->update([
            'status' => SlotStatus::RESERVED,
            'pilot_id' => $user->id,
        ]);
    }
}
