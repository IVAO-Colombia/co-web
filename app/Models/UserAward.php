<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\UserAwardFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string|null $name_en
 * @property string|null $description_en
 * @property int $bronze
 * @property int $silver
 * @property int $gold
 * @property int $platinum
 * @property int $diamond
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $deleted_at
 * @property-read Collection<int, UserAwardReport> $userAwardReports
 * @property-read int|null $user_award_reports_count
 *
 * @method static \Database\Factories\UserAwardFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward withoutTrashed()
 *
 * @mixin \Eloquent
 */
class UserAward extends Model
{
    /**
     * @use HasFactory<UserAwardFactory>
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
        ];
    }

    /**
     * @return HasMany<UserAwardReport, $this>
     */
    public function userAwardReports(): HasMany
    {
        return $this->hasMany(UserAwardReport::class);
    }
}
