<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EventStatus;
use Database\Factories\EventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
