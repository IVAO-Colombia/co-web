<?php

declare(strict_types=1);

namespace App\Services\Ivao;

use App\Services\Ivao\Responses\AtcPosition;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Ivao
{
    private function baseClient(): PendingRequest
    {
        return Http::baseUrl(config('services.ivao.api_url'))
            ->withHeaders([
                'apiKey' => config('services.ivao.api_key'),
            ]);
    }

    /**
     * @return Collection<int, AtcPosition>
     */
    public function atcPositions(string $icao): Collection
    {
        /** @var array<int, mixed>|null $response */
        $response = $this->baseClient()
            ->get("/airports/{$icao}/ATCPositions")
            ->json();

        if (! $response) {
            return collect();
        }

        return collect($response)
            ->map(fn (array $position) => new AtcPosition(
                id: $position['id'],
                airportId: $position['airportId'],
                atcCallsign: $position['atcCallsign'],
                composePosition: $position['composePosition'],
                middleIdentifier: $position['middleIdentifier'],
                position: $position['position'],
                order: $position['order'],
                frequency: $position['frequency'],
                radarRange: $position['radarRange'] ?? null,
            ));
    }
}
