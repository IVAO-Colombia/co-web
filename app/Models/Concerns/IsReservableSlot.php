<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\SlotStatus;
use App\Models\AtcSlot;
use App\Models\PilotSlot;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

/**
 * @phpstan-require-implements PilotSlot
 * @phpstan-require-implements AtcSlot
 **/
trait IsReservableSlot
{
    /**
     * @param  Builder<static>  $query
     */
    #[Scope]
    public function reserved(Builder $query): void
    {
        $query->whereIn('status', [SlotStatus::RESERVED, SlotStatus::CONFIRMED]);
    }

    public function isReserved(): bool
    {
        return in_array($this->status, [SlotStatus::RESERVED, SlotStatus::CONFIRMED]);
    }

    /**
     * @param  Builder<static>  $query
     */
    #[Scope]
    public function available(Builder $query): void
    {
        $query->where('status', SlotStatus::AVAILABLE);
    }

    public function confirm(): void
    {
        abort_if($this->status !== SlotStatus::RESERVED, 409, 'This slot cannot be confirmed.');

        $this->update(['status' => SlotStatus::CONFIRMED]);
    }

    public function cancel(): void
    {
        abort_if(! $this->isReserved(), 409, 'This slot is not reserved.');

        $updates = ['status' => SlotStatus::AVAILABLE];

        if ($this instanceof AtcSlot) {
            $updates['atc_id'] = null;
            $updates['ivao_booking'] = null;
        }

        if ($this instanceof PilotSlot) {
            $updates['pilot_id'] = null;
        }

        $this->update($updates);
    }
}
