<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\AtcPosition;
use App\Models\AtcPositionFra;
use App\Services\Ivao\Ivao;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ivao:fetch-atc-position-fras')]
#[Description('Fetches the current ATC position FRAs from IVAO and updates the database accordingly.')]
class IvaoFetchAtcPositionFras extends Command
{
    public function handle(): int
    {
        $this->info('Fetching ATC position FRAs from IVAO...');

        $fras = app(Ivao::class)->allAtcPositionFras();
        if (empty($fras)) {
            $this->error('Failed to fetch ATC position FRAs from IVAO.');

            return self::FAILURE;
        }

        // Pre-load all local ATC positions keyed by their IVAO ID to avoid N+1 queries.
        $atcPositions = AtcPosition::all()->keyBy('ivao_id');

        $seenIvaoIds = [];

        foreach ($fras as $fra) {
            $ivaoPositionId = $fra['atcPositionId'] ?? $fra['subcenterId'];
            $atcPosition = $atcPositions->get($ivaoPositionId);

            if (! $atcPosition) {
                $embedded = $fra['atcPosition'] ?? $fra['subcenter'] ?? null;

                if (! $embedded) {
                    $this->warn("Skipping FRA #{$fra['id']}: no local or embedded ATC position found for IVAO position ID {$ivaoPositionId}.");

                    continue;
                }

                $airportId = $embedded['airportId'] ?? $embedded['centerId'];

                $atcPosition = AtcPosition::updateOrCreate(
                    ['ivao_id' => $ivaoPositionId],
                    [
                        'airport_id' => $airportId,
                        'atc_callsign' => $embedded['atcCallsign'],
                        'compose_position' => $embedded['composePosition'],
                        'middle_identifier' => $embedded['middleIdentifier'],
                        'position' => $embedded['position'],
                    ]
                );

                // Cache for subsequent FRAs referencing the same position.
                $atcPositions->put($ivaoPositionId, $atcPosition);
            }

            AtcPositionFra::updateOrCreate(
                ['ivao_id' => $fra['id']],
                [
                    'atc_position_id' => $atcPosition->id,
                    'atc_compose_position' => $atcPosition->compose_position,
                    'ivao_user_id' => $fra['userId'],
                    'ivao_atc_position_id' => $fra['atcPositionId'],
                    'ivao_subcenter_id' => $fra['subcenterId'],
                    'start_time' => $fra['startTime'],
                    'end_time' => $fra['endTime'],
                    'monday' => $fra['dayMon'],
                    'tuesday' => $fra['dayTue'],
                    'wednesday' => $fra['dayWed'],
                    'thursday' => $fra['dayThu'],
                    'friday' => $fra['dayFri'],
                    'saturday' => $fra['daySat'],
                    'sunday' => $fra['daySun'],
                    'date' => $fra['date'],
                    'min_atc' => $fra['minAtc'],
                    'active' => $fra['active'],
                    'is_blacklist' => $fra['isBlacklist'],
                ]
            );

            $seenIvaoIds[] = $fra['id'];
        }

        $deleted = AtcPositionFra::whereNotIn('ivao_id', $seenIvaoIds)->delete();

        $this->info('ATC position FRAs have been successfully updated.');

        if ($deleted > 0) {
            $this->info("Removed {$deleted} stale FRA(s) no longer returned by the API.");
        }

        return self::SUCCESS;
    }
}
