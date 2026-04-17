<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SlotStatus;
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
 * @property string|null $flight_number
 * @property string $callsign
 * @property string $aircraft
 * @property string $origin
 * @property string $destination
 * @property CarbonImmutable $departs_at
 * @property string|null $gate
 * @property SlotStatus $status
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read Event|null $event
 * @property-read User|null $pilot
 *
 * @method static \Database\Factories\PilotSlotFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot whereAircraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot whereCallsign($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot whereDepartsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot whereDestination($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot whereEventId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot whereFlightNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot whereGate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot whereOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot wherePilotId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PilotSlot whereUpdatedAt($value)
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
            'pilot_id' => 'integer',
            'departs_at' => 'datetime',
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
    public function pilot(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
