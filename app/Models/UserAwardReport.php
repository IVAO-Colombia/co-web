<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\UserAwardReportStatus;
use Database\Factories\UserAwardReportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
