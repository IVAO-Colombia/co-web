<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\AtcPosition;
use App\Services\Ivao\Ivao;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('ivao:fetch-atc-positions')]
#[Description('Fetches the current ATC positions from IVAO and updates the database accordingly.')]
class IvaoFetchAtcPositions extends Command
{
    public function handle(): int
    {
        $this->info('Fetching ATC positions from IVAO...');

        // Fetch the ATC positions from the IVAO API
        $atcPositions = app(Ivao::class)->allAtcPositions();
        if (empty($atcPositions)) {
            $this->error('Failed to fetch ATC positions from IVAO.');

            return self::FAILURE;
        }

        // Update the database with the fetched ATC positions
        foreach ($atcPositions as $position) {
            AtcPosition::updateOrCreate(
                ['ivao_id' => $position['id']],
                [
                    'airport_id' => $position['airportId'] ?? $position['centerId'],
                    'atc_callsign' => $position['atcCallsign'],
                    'compose_position' => $position['composePosition'],
                    'middle_identifier' => $position['middleIdentifier'],
                    'position' => $position['position'],
                    'frequency' => $position['frequency'],
                ]
            );
        }

        $this->info('ATC positions have been successfully updated.');

        return self::SUCCESS;
    }
}
