<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\SlotStatus;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

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
}
