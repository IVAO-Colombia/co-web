<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SlotStatus;
use Carbon\CarbonImmutable;
use Database\Factories\AtcSlotFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $event_id
 * @property int|null $atc_id
 * @property string $callsign
 * @property string $starts_at
 * @property string $ends_at
 * @property SlotStatus $status
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read User|null $atc
 * @property-read Event|null $event
 *
 * @method static \Database\Factories\AtcSlotFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot whereAtcId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot whereCallsign($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot whereEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot whereStartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcSlot withoutTrashed()
 *
 * @mixin \Eloquent
 */
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
