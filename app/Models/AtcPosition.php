<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int|null $ivao_id
 * @property string $airport_id
 * @property string $atc_callsign
 * @property string $compose_position
 * @property string|null $middle_identifier
 * @property string $position
 * @property string|null $frequency
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, AtcPositionFra> $fras
 * @property-read int|null $fras_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcPosition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcPosition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AtcPosition query()
 *
 * @mixin \Eloquent
 */
class AtcPosition extends Model
{
    /**
     * @return HasMany<AtcPositionFra, $this>
     */
    public function fras(): HasMany
    {
        return $this->hasMany(AtcPositionFra::class, 'atc_position_id');
    }
}
