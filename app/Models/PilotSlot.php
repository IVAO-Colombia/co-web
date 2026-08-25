<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PilotSlotCategory;
use App\Enums\SlotStatus;
use App\Models\Concerns\IsReservableSlot;
use Carbon\CarbonImmutable;
use Database\Factories\PilotSlotFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $event_id
 * @property int|null $pilot_id
 * @property string $airline_icao
 * @property string $flight_number
 * @property string $aircraft
 * @property string $origin
 * @property string $destination
 * @property PilotSlotCategory $category
 * @property CarbonImmutable $departs_at
 * @property CarbonImmutable|null $arrives_at
 * @property string|null $gate
 * @property SlotStatus $status
 * @property CarbonImmutable|null $reminder_sent_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read Event|null $event
 * @property-read User|null $pilot
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot available()
 * @method static \Database\Factories\PilotSlotFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot reserved()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot withoutTrashed()
 *
 * @mixin \Eloquent
 */
class PilotSlot extends Model
{
    /**
     * @use HasFactory<PilotSlotFactory>
     */
    use HasFactory, IsReservableSlot, SoftDeletes;

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
            'pilot_id' => 'integer',
            'category' => PilotSlotCategory::class,
            'departs_at' => 'immutable_datetime',
            'arrives_at' => 'immutable_datetime',
            'status' => SlotStatus::class,
            'reminder_sent_at' => 'immutable_datetime',
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
    public function pilot(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
