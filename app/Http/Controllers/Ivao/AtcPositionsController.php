<?php

declare(strict_types=1);

namespace App\Http\Controllers\Ivao;

use App\Http\Controllers\Controller;
use App\Models\AtcPosition;
use App\Services\Ivao\Ivao;
use Illuminate\Support\Collection;

class AtcPositionsController extends Controller
{
    /**
     * @return Collection<int, AtcPosition>
     */
    public function __invoke(string $icao): Collection
    {
        return app(Ivao::class)
            ->atcPositions($icao)
            ->map(fn ($position) => AtcPosition::updateOrCreate(
                ['ivao_id' => $position->id],
                [
                    'airport_id' => $position['airportId'],
                    'atc_callsign' => $position['atcCallsign'],
                    'compose_position' => $position['composePosition'],
                    'middle_identifier' => $position['middleIdentifier'],
                    'position' => $position['position'],
                    'frequency' => $position['frequency'],
                ]
            ));
    }
}
