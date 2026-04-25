<?php

declare(strict_types=1);

namespace App\Services\Ivao;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
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
     * @return array<int, mixed>
     */
    public function atcPositions(string $icao): array
    {
        /** @var array<int, mixed>|null $response */
        $response = $this->baseClient()
            ->get("/airports/{$icao}/ATCPositions")
            ->json();

        if (! $response) {
            return [];
        }

        return $response;
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

    /**
     * Check whether a member is eligible to reserve an ATC slot.
     *
     * Returns the raw response so the caller can inspect the HTTP status code:
     *   200 – allowed, 401 – re-authentication required, 403 – not allowed.
     *
     * @param  string  $startDate  ISO-8601 datetime with milliseconds and Z suffix, e.g. "2023-08-07T09:00:00.000Z"
     */
    public function checkAtcReservationEligibility(string $callsign, int $vid, string $startDate): Response
    {
        return $this->baseClient()
            ->get("/fras/check/{$callsign}/{$vid}", [
                'startDate' => $startDate,
            ]);
    }

    /**
     * Create an ATC booking on IVAO.
     *
     * @param  string  $startDate  ISO-8601 datetime, e.g. "2023-09-10T14:00:00+00:00"
     * @param  string  $endDate  ISO-8601 datetime, e.g. "2023-09-10T15:00:00+00:00"
     */
    public function createAtcBooking(string $atcPosition, string $startDate, string $endDate): Response
    {
        return $this->baseClient()
            ->post('/atc/bookings', [
                'atcPosition' => $atcPosition,
                'training' => null,
                'voice' => true,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);
    }
}
