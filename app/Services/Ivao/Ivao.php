<?php

declare(strict_types=1);

namespace App\Services\Ivao;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Ivao
{
    private function baseClientApiKey(): PendingRequest
    {
        return Http::baseUrl(config('services.ivao.api_url'))
            ->withHeaders([
                'apiKey' => config('services.ivao.api_key'),
            ]);
    }

    private function baseClient(): PendingRequest
    {
        return Http::baseUrl(config('services.ivao.api_url'));
    }

    /**
     * @return array<int, mixed>
     */
    public function atcPositions(string $icao): array
    {
        /** @var array<int, mixed>|null $response */
        $response = $this->baseClientApiKey()
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
            $response = $this->baseClientApiKey()
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
        $result = $this->baseClientApiKey()
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
        return $this->baseClientApiKey()
            ->get("/fras/check/{$callsign}/{$vid}", [
                'startDate' => $startDate,
            ]);
    }

    /**
     * @param  string  $startDate  ISO-8601 datetime, e.g. "2023-09-10T14:00:00+00:00"
     * @param  string  $endDate  ISO-8601 datetime, e.g. "2023-09-10T15:00:00+00:00"
     */
    public function createAtcBookingAsUser(
        string $accessToken,
        string $atcPosition,
        string $startDate,
        string $endDate,
    ): Response {
        return $this->baseClient()
            ->withToken($accessToken)
            ->post('/atc/bookings', [
                'atcPosition' => $atcPosition,
                'training' => null, // it can be training, exam
                'voice' => true,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);
    }

    /**
     * Delete an ATC booking as the authenticated user.
     *
     * Returns the raw response so the caller can inspect the HTTP status code:
     *   200 – deleted, 401 – re-authentication required, 403 – forbidden, 404 – not found.
     */
    public function deleteAtcBookingAsUser(string $accessToken, int $bookingId): Response
    {
        return $this->baseClient()
            ->withToken($accessToken)
            ->delete("/atc/bookings/{$bookingId}");
    }

    public function refreshAccessToken(string $refreshToken): Response
    {
        return $this->baseClient()->post('/oauth/token', [
            'grant_type' => 'refresh_token',
            'client_id' => config('services.ivao.client_id'),
            'client_secret' => config('services.ivao.client_secret'),
            'refresh_token' => $refreshToken,
        ]);
    }

    /**
     *  Get active flights from Whazzup that departed from SK* airports
     *
     * @return array{flights: array<int, mixed>, last_updated: null|string}
     */
    public function getWhazzupFlights(): array
    {
        $flightsCacheKey = 'ivao:whazzup:co';
        $lastUpdatedCacheKey = 'ivao:whazzup:co:last_updated';
        $lock = Cache::lock('ivao:whazzup:lock', 15);

        if (! $lock->get()) {
            return [
                'flights' => Cache::get($flightsCacheKey, []),
                'last_updated' => Cache::get($lastUpdatedCacheKey, now()->toISOString()),
            ];
        }

        /** @var array<int, mixed> $flights */
        $flights = Cache::remember($flightsCacheKey, $lastUpdated = now()->addMinutes(5), function () {
            $response = $this->baseClient()->get('/tracker/whazzup');

            if ($response->failed()) {
                return [];
            }

            $data = $response->json();

            if (! is_array($data) || ! isset($data['clients']['pilots']) || ! is_array($data['clients']['pilots'])) {
                return [];
            }

            return collect($data['clients']['pilots'])
                ->reject(function (array $flight): bool {
                    $flightPlan = $flight['flightPlan'] ?? [];
                    $lastTrack = $flight['lastTrack'] ?? [];

                    return
                        (
                            ! str_starts_with($flightPlan['departureId'] ?? '', 'SK')
                            && ! str_starts_with($flightPlan['arrivalId'] ?? '', 'SK')
                        ) || (
                            ! $lastTrack['latitude']
                            && ! $lastTrack['longitude']
                        );
                })
                ->map(fn (array $flight): array => [
                    'flight_id' => $flight['id'] ?? null,
                    'ivao_id' => $flight['userId'] ?? null,
                    'callsign' => $flight['callsign'],
                    'airline' => strtoupper(substr((string) $flight['callsign'], 0, 3)),
                    'departure_icao' => $flight['flightPlan']['departureId'],
                    'arrival_icao' => $flight['flightPlan']['arrivalId'],
                    'aircraft' => $flight['flightPlan']['aircraftId'] ?? 'UNKNOWN',
                    'aircraft_model' => $flight['flightPlan']['aircraft']['model'] ?? null,
                    'latitude' => $flight['lastTrack']['latitude'],
                    'longitude' => $flight['lastTrack']['longitude'],
                    'altitude' => (int) $flight['lastTrack']['altitude'],
                    'ground_speed' => (int) $flight['lastTrack']['groundSpeed'],
                    'heading' => (int) ($flight['lastTrack']['heading'] ?? 0),
                    'state' => $flight['lastTrack']['state'] ?? 'Unknown',
                    'timestamp' => $flight['lastTrack']['timestamp'] ?? now()->toISOString(),
                ])
                ->values()
                ->toArray();
        });

        $lock->release();

        return [
            'flights' => $flights,
            'last_updated' => Cache::remember(
                $lastUpdatedCacheKey,
                $lastUpdated,
                fn () => $lastUpdated->toISOString()
            ),
        ];
    }
}
