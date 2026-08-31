<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EventStatus;
use App\Enums\EventType;
use Carbon\CarbonImmutable;
use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int|null $parent_event_id
 * @property string $name
 * @property string $description
 * @property string|null $name_en
 * @property string|null $description_en
 * @property string $slug
 * @property string|null $image_url
 * @property EventType $type
 * @property array<array-key, mixed> $tags
 * @property bool $pilot_slots_enabled
 * @property bool $atc_slots_enabled
 * @property string $locations
 * @property CarbonImmutable $starts_at
 * @property CarbonImmutable|null $ends_at
 * @property EventStatus $status
 * @property bool $is_recurring
 * @property int|null $recurrence_interval
 * @property array<array-key, int>|null $recurrence_weekdays
 * @property CarbonImmutable|null $recurrence_ends_at
 * @property int $created_by
 * @property int|null $assigned_to
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read User|null $assignedTo
 * @property-read Collection<int, AtcSlot> $atcSlots
 * @property-read int|null $atc_slots_count
 * @property-read User|null $createdBy
 * @property-read Event|null $parent
 * @property-read Collection<int, Event> $occurrences
 * @property-read int|null $occurrences_count
 * @property-read Collection<int, PilotSlot> $pilotSlots
 * @property-read int|null $pilot_slots_count
 * @property-read Collection<int, UserAwardReport> $userAwardReports
 * @property-read int|null $user_award_reports_count
 *
 * @method static Builder<static>|Event active()
 * @method static Builder<static>|Event ended()
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * @method static Builder<static>|Event newModelQuery()
 * @method static Builder<static>|Event newQuery()
 * @method static Builder<static>|Event onlyTrashed()
 * @method static Builder<static>|Event query()
 * @method static Builder<static>|Event withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Event withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Event extends Model
{
    /**
     * @use HasFactory<EventFactory>
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
            'parent_event_id' => 'integer',
            'pilot_slots_enabled' => 'boolean',
            'atc_slots_enabled' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_recurring' => 'boolean',
            'recurrence_interval' => 'integer',
            'recurrence_weekdays' => 'json',
            'recurrence_ends_at' => 'datetime',
            'created_by' => 'integer',
            'assigned_to' => 'integer',
            'tags' => 'json',
            'type' => EventType::class,
            'status' => EventStatus::class,
        ];
    }

    /**
     * @return HasMany<PilotSlot, $this>
     */
    public function pilotSlots(): HasMany
    {
        return $this->hasMany(PilotSlot::class);
    }

    /**
     * @return HasMany<AtcSlot, $this>
     */
    public function atcSlots(): HasMany
    {
        return $this->hasMany(AtcSlot::class);
    }

    /**
     * @return HasMany<UserAwardReport, $this>
     */
    public function userAwardReports(): HasMany
    {
        return $this->hasMany(UserAwardReport::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * The recurring template this occurrence belongs to.
     *
     * @return BelongsTo<Event, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'parent_event_id');
    }

    /**
     * The generated occurrences belonging to this recurring template.
     *
     * @return HasMany<Event, $this>
     */
    public function occurrences(): HasMany
    {
        return $this->hasMany(Event::class, 'parent_event_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function hasReservedSlots(): bool
    {
        if ($this->pilotSlots()->reserved()->exists()) {
            return true;
        }

        return $this->atcSlots()->reserved()->exists();
    }

    /**
     * @param  Builder<Event>  $query
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query
            ->where('status', EventStatus::ACTIVE)
            ->where('is_recurring', false)
            ->where(function (Builder $query): void {
                $query
                    ->where(function (Builder $query): void {
                        $query->whereNotNull('ends_at')
                            ->where('ends_at', '>=', now());
                    })
                    ->orWhere(function (Builder $query): void {
                        $query->whereNull('ends_at')
                            ->where('starts_at', '>=', now()->startOfDay());
                    });
            });
    }

    /**
     * @param  Builder<Event>  $query
     */
    #[Scope]
    protected function ended(Builder $query): void
    {
        $query
            ->where('is_recurring', false)
            ->where(function (Builder $query): void {
                $query
                    ->where(function (Builder $query): void {
                        $query->whereNotNull('ends_at')
                            ->where('ends_at', '<', now());
                    })
                    ->orWhere(function (Builder $query): void {
                        $query->whereNull('ends_at')
                            ->where('starts_at', '<', now()->startOfDay());
                    });
            });
    }
}
