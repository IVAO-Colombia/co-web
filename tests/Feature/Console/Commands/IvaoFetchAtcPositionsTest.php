<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Models\AtcPosition;
use App\Services\Ivao\Ivao;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IvaoFetchAtcPositionsTest extends TestCase
{
    /**
     * @return array<int, mixed>
     */
    private function fakePosition(int $id = 1): array
    {
        return [
            'id' => $id,
            'airportId' => 'SKBO',
            'atcCallsign' => 'SKBO_TWR',
            'composePosition' => 'SKBO_TWR',
            'middleIdentifier' => 'TWR',
            'position' => 'TWR',
            'frequency' => '118.1',
        ];
    }

    #[Test]
    public function it_creates_new_atc_positions(): void
    {
        $this->mock(Ivao::class)
            ->shouldReceive('allAtcPositions')
            ->once()
            ->andReturn([$this->fakePosition(42)]);

        $this->artisan('ivao:fetch-atc-positions')
            ->expectsOutput('Fetching ATC positions from IVAO...')
            ->expectsOutput('ATC positions have been successfully updated.')
            ->assertSuccessful();

        $this->assertDatabaseHas('atc_positions', [
            'ivao_id' => 42,
            'airport_id' => 'SKBO',
            'atc_callsign' => 'SKBO_TWR',
            'compose_position' => 'SKBO_TWR',
            'middle_identifier' => 'TWR',
            'position' => 'TWR',
            'frequency' => '118.1',
        ]);
    }

    #[Test]
    public function it_updates_existing_atc_positions(): void
    {
        AtcPosition::create([
            'ivao_id' => 42,
            'airport_id' => 'SKBO',
            'atc_callsign' => 'SKBO_TWR',
            'compose_position' => 'SKBO_TWR',
            'middle_identifier' => 'TWR',
            'position' => 'TWR',
            'frequency' => '118.1',
        ]);

        $updated = $this->fakePosition(42);
        $updated['frequency'] = '119.0';
        $updated['atcCallsign'] = 'SKBO_TWR_2';

        $this->mock(Ivao::class)
            ->shouldReceive('allAtcPositions')
            ->once()
            ->andReturn([$updated]);

        $this->artisan('ivao:fetch-atc-positions')->assertSuccessful();

        $this->assertDatabaseCount('atc_positions', 1);
        $this->assertDatabaseHas('atc_positions', [
            'ivao_id' => 42,
            'atc_callsign' => 'SKBO_TWR_2',
            'frequency' => '119.0',
        ]);
    }

    #[Test]
    public function it_uses_center_id_when_airport_id_is_absent(): void
    {
        $position = $this->fakePosition(10);
        unset($position['airportId']);
        $position['centerId'] = 'SKEC';

        $this->mock(Ivao::class)
            ->shouldReceive('allAtcPositions')
            ->once()
            ->andReturn([$position]);

        $this->artisan('ivao:fetch-atc-positions')->assertSuccessful();

        $this->assertDatabaseHas('atc_positions', [
            'ivao_id' => 10,
            'airport_id' => 'SKEC',
        ]);
    }

    #[Test]
    public function it_shows_an_error_when_the_api_returns_empty(): void
    {
        $this->mock(Ivao::class)
            ->shouldReceive('allAtcPositions')
            ->once()
            ->andReturn([]);

        $this->artisan('ivao:fetch-atc-positions')
            ->expectsOutput('Failed to fetch ATC positions from IVAO.')
            ->assertFailed();

        $this->assertDatabaseCount('atc_positions', 0);
    }
}
