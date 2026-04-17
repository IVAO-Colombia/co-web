<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserAwardFactory;
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
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property \Carbon\CarbonImmutable|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserAwardReport> $userAwardReports
 * @property-read int|null $user_award_reports_count
 * @method static \Database\Factories\UserAwardFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward whereBronze($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward whereDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward whereDiamond($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward whereGold($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward wherePlatinum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward whereSilver($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserAward withoutTrashed()
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
