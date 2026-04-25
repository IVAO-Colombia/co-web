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
 * @property int $created_by
 * @property int|null $assigned_to
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read \App\Models\User|null $assignedTo
 * @property-read Collection<int, \App\Models\AtcSlot> $atcSlots
 * @property-read int|null $atc_slots_count
 * @property-read \App\Models\User|null $createdBy
 * @property-read Collection<int, \App\Models\PilotSlot> $pilotSlots
 * @property-read int|null $pilot_slots_count
 * @property-read Collection<int, \App\Models\UserAwardReport> $userAwardReports
 * @property-read int|null $user_award_reports_count
 * @method static Builder<static>|Event active()
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * @method static Builder<static>|Event newModelQuery()
 * @method static Builder<static>|Event newQuery()
 * @method static Builder<static>|Event onlyTrashed()
 * @method static Builder<static>|Event query()
 * @method static Builder<static>|Event withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Event withoutTrashed()
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
            'pilot_slots_enabled' => 'boolean',
            'atc_slots_enabled' => 'boolean',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
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
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param  Builder<Event>  $query
     */
    #[Scope]
    protected function active(Builder $query): void
    {
        $query
            ->where('status', EventStatus::ACTIVE)
            ->where('starts_at', '>=', now()->subDay());
    }
}
