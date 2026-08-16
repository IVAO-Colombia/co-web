<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserAwardReportStatus;
use Carbon\CarbonImmutable;
use Database\Factories\UserAwardReportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int $award_id
 * @property int $event_id
 * @property string $callsign
 * @property UserAwardReportStatus $status
 * @property int $points
 * @property string $observations
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read UserAward|null $award
 * @property-read Event|null $event
 * @property-read User $user
 *
 * @method static \Database\Factories\UserAwardReportFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAwardReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAwardReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAwardReport onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAwardReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAwardReport withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAwardReport withoutTrashed()
 *
 * @mixin \Eloquent
 */
class UserAwardReport extends Model
{
    /**
     * @use HasFactory<UserAwardReportFactory>
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
            'user_id' => 'integer',
            'award_id' => 'integer',
            'event_id' => 'integer',
            'status' => UserAwardReportStatus::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<UserAward, $this>
     */
    public function award(): BelongsTo
    {
        return $this->belongsTo(UserAward::class);
    }

    /**
     * @return BelongsTo<Event, $this>
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
