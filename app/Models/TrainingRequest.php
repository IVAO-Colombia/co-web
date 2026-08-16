<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TrainingRequestStatus;
use App\Enums\TrainingRequestType;
use Carbon\CarbonImmutable;
use Database\Factories\TrainingRequestFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property TrainingRequestType $type
 * @property string $category
 * @property TrainingRequestStatus $status
 * @property CarbonImmutable|null $occurs_at
 * @property string|null $internal_observations
 * @property string|null $public_observations
 * @property string $request_observations
 * @property int|null $trainer_id
 * @property int $trainee_id
 * @property int|null $event_id
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Event|null $event
 * @property-read User $trainee
 * @property-read User|null $trainer
 *
 * @method static \Database\Factories\TrainingRequestFactory factory($count = null, $state = [])
 * @method static Builder<static>|TrainingRequest forTrainee(int $userId)
 * @method static Builder<static>|TrainingRequest newModelQuery()
 * @method static Builder<static>|TrainingRequest newQuery()
 * @method static Builder<static>|TrainingRequest ofType(\App\Enums\TrainingRequestType $type)
 * @method static Builder<static>|TrainingRequest pending()
 * @method static Builder<static>|TrainingRequest query()
 *
 * @mixin \Eloquent
 */
class TrainingRequest extends Model
{
    /** @use HasFactory<TrainingRequestFactory> */
    use HasFactory;

    /** @return array{type: class-string<TrainingRequestType>, status: class-string<TrainingRequestStatus>, occurs_at: 'immutable_datetime'} */
    #[\Override]
    protected function casts(): array
    {
        return [
            'type' => TrainingRequestType::class,
            'status' => TrainingRequestStatus::class,
            'occurs_at' => 'immutable_datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function trainee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    /** @return BelongsTo<User, $this> */
    public function trainer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainer_id');
    }

    /** @return BelongsTo<Event, $this> */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /** @param Builder<static> $query */
    public function scopePending(Builder $query): void
    {
        $query->where('status', TrainingRequestStatus::Pending);
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeForTrainee(Builder $query, int $userId): void
    {
        $query->where('trainee_id', $userId);
    }

    /**
     * @param  Builder<static>  $query
     */
    public function scopeOfType(Builder $query, TrainingRequestType $type): void
    {
        $query->where('type', $type);
    }

    public function cancel(): void
    {
        $this->status = TrainingRequestStatus::Cancelled;
        $this->save();
    }
}
