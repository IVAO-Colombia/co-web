<?php

declare(strict_types=1);

namespace App\Services\Ivao;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
     * @return array<int, mixed>
     */
    public function getWhazzupFlights(): array
    {

        $lockKey = 'ivao:whazzup:lock';
        $lastRequestKey = 'ivao:whazzup:last_request';

        if (! Cache::lock($lockKey, 1)->get()) {
            return Cache::get('ivao:whazzup:sk', []);
        }

        try {
            $lastRequest = Cache::get($lastRequestKey, 0);
            $timeSinceLastRequest = now()->timestamp - $lastRequest;

            if ($timeSinceLastRequest < 15) {
                sleep(15 - $timeSinceLastRequest);
            }

            $etagKey = 'ivao:whazzup:etag';
            $currentEtag = Cache::get($etagKey);

            $headers = [];
            if ($currentEtag) {
                $headers['If-None-Match'] = $currentEtag;
            }

            $response = $this->baseClient()
                ->withHeaders($headers)
                ->get('/tracker/whazzup');

            if ($response->status() === 304) {
                Cache::put($lastRequestKey, now()->timestamp, 3600);

                return Cache::get('ivao:whazzup:sk', []);
            }

            if (! $response->successful()) {
                Log::warning('IVAO whazzup request failed', [
                    'status' => $response->status(),
                ]);

                return Cache::get('ivao:whazzup:sk', []);
            }

            $etag = $response->header('ETag');
            if ($etag !== null) {
                Cache::put($etagKey, $etag, 3600);
            }

            /** @var array{clients?: array{pilots?: array<int, array<string, mixed>>}}|null $data */
            $data = $response->json();

            if (! is_array($data) || ! isset($data['clients']['pilots']) || ! is_array($data['clients']['pilots'])) {
                Cache::put($lastRequestKey, now()->timestamp, 3600);
                Log::info('IVAO whazzup: no pilots key in response', ['keys' => is_array($data) ? array_keys($data) : null]);

                return [];
            }

            $filtered = collect($data['clients']['pilots'])
                ->map(fn (array $pilot): ?array => $this->parseFlightData($pilot))
                ->filter()
                ->filter(function (array $flight): bool {
                    $dep = strtoupper((string) ($flight['dep_icao'] ?? ''));
                    $arr = strtoupper((string) ($flight['arr_icao'] ?? ''));

                    return $dep !== '' || $arr !== '';
                })
                ->filter(function (array $flight): bool {
                    $dep = strtoupper((string) ($flight['dep_icao'] ?? ''));
                    $arr = strtoupper((string) ($flight['arr_icao'] ?? ''));

                    return str_starts_with($dep, 'SK') || str_starts_with($arr, 'SK');
                })
                ->values()
                ->all();

            Cache::put('ivao:whazzup:sk', $filtered, 15);
            Cache::put($lastRequestKey, now()->timestamp, 3600);

            return $filtered;
        } finally {
            Cache::lock($lockKey)->forceRelease();
        }
    }

    /**
     * Parse flight data from the whazzup response
     *
     * @param  array<string, mixed>  $pilot
     * @return array<string, mixed>|null
     */
    private function parseFlightData(array $pilot): ?array
    {
        if (
            ! isset($pilot['callsign']) ||
            ! isset($pilot['flightPlan']) ||
            ! isset($pilot['lastTrack'])
        ) {
            return null;
        }

        $flightPlan = $pilot['flightPlan'];
        $lastTrack = $pilot['lastTrack'];

        if (
            ! isset($flightPlan['departureId'], $flightPlan['arrivalId']) ||
            ! isset($lastTrack['latitude'], $lastTrack['longitude'], $lastTrack['altitude'], $lastTrack['groundSpeed'])
        ) {
            return null;
        }

        $lat = (float) $lastTrack['latitude'];
        $lon = (float) $lastTrack['longitude'];

        if ($lat == 0 && $lon == 0) {
            return null;
        }

        return [
            'flight_id' => $pilot['id'] ?? null,
            'ivao_id' => $pilot['userId'] ?? null,
            'callsign' => $pilot['callsign'],
            'airline' => $this->extractAirlineFromCallsign($pilot['callsign']),
            'dep_icao' => $flightPlan['departureId'],
            'arr_icao' => $flightPlan['arrivalId'],
            'aircraft' => $flightPlan['aircraftId'] ?? 'UNKNOWN',
            'aircraft_model' => $flightPlan['aircraft']['model'] ?? null,
            'latitude' => $lat,
            'longitude' => $lon,
            'altitude' => (int) $lastTrack['altitude'],
            'ground_speed' => (int) $lastTrack['groundSpeed'],
            'heading' => (int) ($lastTrack['heading'] ?? 0),
            'state' => $lastTrack['state'] ?? 'Unknown',
            'timestamp' => $lastTrack['timestamp'] ?? now()->toIso8601String(),
        ];

    }

    private function extractAirlineFromCallsign(string $callsign): string
    {
        return strtoupper(substr($callsign, 0, 3));
    }
}
