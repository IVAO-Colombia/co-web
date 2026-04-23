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
            ->map(fn (array $position): AtcPosition => new AtcPosition(
                id: $position['id'],
                airportId: $position['airportId'] ?? $position['centerId'],
                atcCallsign: $position['atcCallsign'],
                composePosition: $position['composePosition'],
                middleIdentifier: $position['middleIdentifier'],
                position: $position['position'],
                order: $position['order'],
                frequency: $position['frequency'],
                radarRange: $position['radarRange'] ?? null,
            ));
    }

    /**
     * @return array<int, mixed>
     */
    public function allAtcPositionFras(): array
    {
        $items = [];
        $page = 1;

        do {
            /** @var array{totalItems: int, perPage: int, page: int, pages: int, items: array<int, mixed>}|null $response */
            $response = $this->baseClient()
                ->get('/fras', [
                    'page' => $page,
                    'countryId' => 'CO',
                    'isActive' => 'true',
                    'members' => 'true',
                    'positions' => 'true',
                    'expand' => 'true',
                ])
                ->json();

            if (! isset($response['items'])) {
                break;
            }

            $items = array_merge($items, $response['items']);
            $totalPages = $response['pages'];
            $page++;
        } while ($page <= $totalPages);

        return $items;
    }

    /**
     * @return array<int, mixed>
     */
    public function allAtcPositions(): array
    {
        /** @var array<int, mixed>|null $result */
        $result = $this->baseClient()
            ->get('/positions/search', [
                'countryId' => 'CO',
                'limit' => '100',
            ])
            ->json();

        return $result ?? [];
    }
}
