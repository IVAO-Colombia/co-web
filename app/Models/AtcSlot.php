<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SlotStatus;
use Database\Factories\AtcSlotFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AtcSlot extends Model
{
    /**
     * @use HasFactory<AtcSlotFactory>
     */
    use HasFactory, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    #[\Override]
    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'event_id' => 'integer',
            'atc_id' => 'integer',
            'status' => SlotStatus::class,
        ];
    }

    /**
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function atc(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
