<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AtcTraining;
use App\Enums\PilotTraining;
use App\Enums\TrainingNoteVisibility;
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
 * @property list<array{at: string, by_id: int, by_name: string, trainer_id: int|null, trainer_name: string|null}>|null $assignment_history
 * @property int $trainee_id
 * @property int|null $event_id
 * @property CarbonImmutable|null $ivao_reminder_sent_at
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

    /** @return array{type: class-string<TrainingRequestType>, status: class-string<TrainingRequestStatus>, occurs_at: 'immutable_datetime', assignment_history: 'array', ivao_reminder_sent_at: 'immutable_datetime'} */
    #[\Override]
    protected function casts(): array
    {
        return [
            'type' => TrainingRequestType::class,
            'status' => TrainingRequestStatus::class,
            'occurs_at' => 'immutable_datetime',
            'assignment_history' => 'array',
            'ivao_reminder_sent_at' => 'immutable_datetime',
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
        $query->where('status', TrainingRequestStatus::PENDING);
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
        $this->status = TrainingRequestStatus::CANCELLED;
        $this->save();
    }

    /**
     * The human-readable label for this request's category, resolved
     * through the enum matching its type.
     */
    public function categoryLabel(): string
    {
        return $this->type === TrainingRequestType::ATC
            ? AtcTraining::from($this->category)->label()
            : PilotTraining::from($this->category)->label();
    }

    /**
     * Whether staff can send another "request it on IVAO" reminder, i.e. one
     * has never been sent, or the cooldown has elapsed.
     */
    public function canSendIvaoReminder(): bool
    {
        return $this->ivao_reminder_sent_at === null
            || $this->ivao_reminder_sent_at->lt(now()->subHours(config('training.ivao_reminder_cooldown_hours')));
    }

    public function markIvaoReminderSent(): void
    {
        $this->ivao_reminder_sent_at = now();
        $this->save();
    }

    /**
     * Assign (or clear, when $trainer is null) the trainer and append the
     * change to the assignment history.
     */
    public function assignTrainer(User $actor, ?User $trainer): void
    {
        $this->trainer_id = $trainer?->id;
        $this->assignment_history = [
            ...($this->assignment_history ?? []),
            [
                'at' => now()->toIso8601String(),
                'by_id' => $actor->id,
                'by_name' => $actor->name,
                'trainer_id' => $trainer?->id,
                'trainer_name' => $trainer?->name,
            ],
        ];

        $this->save();
    }

    /**
     * Append an attributed, timestamped note to the given observations column.
     */
    public function appendNote(User $author, TrainingNoteVisibility $visibility, string $body): void
    {
        $column = $visibility->column();
        $entry = sprintf('[%s] %s: %s', now()->format('Y-m-d H:i'), $author->name, $body);
        $existing = $this->{$column};

        $this->{$column} = $existing === null || $existing === ''
            ? $entry
            : $existing."\n\n".$entry;

        $this->save();
    }
}
