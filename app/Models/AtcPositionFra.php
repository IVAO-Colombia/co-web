<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

/**
 * @property int $id
 * @property int $atc_position_id
 * @property string $atc_compose_position
 * @property int $ivao_id
 * @property int|null $ivao_user_id
 * @property int|null $ivao_atc_position_id
 * @property int|null $ivao_subcenter_id
 * @property string $start_time
 * @property string $end_time
 * @property int $monday
 * @property int $tuesday
 * @property int $wednesday
 * @property int $thursday
 * @property int $friday
 * @property int $saturday
 * @property int $sunday
 * @property string|null $date
 * @property int|null $min_atc
 * @property int $active
 * @property int $is_blacklist
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read \App\Models\AtcPosition $atcPosition
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcPositionFra forIcao(array|string $icao)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcPositionFra newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcPositionFra newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcPositionFra query()
 * @mixin \Eloquent
 */
class AtcPositionFra extends Model
{
    /**
     * @return BelongsTo<AtcPosition, $this>
     */
    public function atcPosition(): BelongsTo
    {
        return $this->belongsTo(AtcPosition::class);
    }

    /**
     * @param  string|array<int, string>  $icao
     */
    #[Scope]
    protected function forIcao(Builder $query, string|array $icao): void
    {
        $query->when(is_array($icao), function ($query) use ($icao) {
            $query->whereIn('atc_compose_position', $icao);
        }, function ($query) use ($icao) {
            $query->where('atc_compose_position', $icao);
        });
    }
}
