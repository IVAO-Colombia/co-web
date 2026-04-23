<?php

declare(strict_types=1);

namespace Tests\Feature\Console\Commands;

use App\Models\AtcPosition;
use App\Models\AtcPositionFra;
use App\Services\Ivao\Ivao;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class IvaoFetchAtcPositionFrasTest extends TestCase
{
    /**
     * @return array<string, mixed>
     */
    /**
     * @return array<string, mixed>
     */
    private function fakeFra(int $id = 1, int $atcPositionId = 100): array
    {
        return [
            'id' => $id,
            'userId' => 999,
            'atcPositionId' => $atcPositionId,
            'subcenterId' => null,
            'startTime' => '08:00',
            'endTime' => '16:00',
            'dayMon' => true,
            'dayTue' => true,
            'dayWed' => true,
            'dayThu' => true,
            'dayFri' => true,
            'daySat' => false,
            'daySun' => false,
            'date' => null,
            'minAtc' => 1,
            'active' => true,
            'isBlacklist' => false,
            'atcPosition' => [
                'id' => $atcPositionId,
                'airportId' => 'SKBO',
                'atcCallsign' => 'SKBO_TWR',
                'composePosition' => 'SKBO_TWR',
                'middleIdentifier' => 'TWR',
                'position' => 'TWR',
            ],
            'subcenter' => null,
        ];
    }

    private function createAtcPosition(int $ivaoId = 100): AtcPosition
    {
        return AtcPosition::create([
            'ivao_id' => $ivaoId,
            'airport_id' => 'SKBO',
            'atc_callsign' => 'SKBO_TWR',
            'compose_position' => 'SKBO_TWR',
            'middle_identifier' => 'TWR',
            'position' => 'TWR',
            'frequency' => '118.1',
        ]);
    }

    #[Test]
    public function it_creates_fras_when_a_matching_atc_position_exists(): void
    {
        $atcPosition = $this->createAtcPosition(100);

        $this->mock(Ivao::class)
            ->shouldReceive('allAtcPositionFras')
            ->once()
            ->andReturn([$this->fakeFra(1, 100)]);

        $this->artisan('ivao:fetch-atc-position-fras')
            ->expectsOutput('Fetching ATC position FRAs from IVAO...')
            ->expectsOutput('ATC position FRAs have been successfully updated.')
            ->assertSuccessful();

        $this->assertDatabaseHas('atc_position_fras', [
            'ivao_id' => 1,
            'atc_position_id' => $atcPosition->id,
            'atc_compose_position' => $atcPosition->compose_position,
            'ivao_atc_position_id' => 100,
            'ivao_user_id' => 999,
            'start_time' => '08:00',
            'end_time' => '16:00',
            'monday' => 1,
            'friday' => 1,
            'saturday' => 0,
            'min_atc' => 1,
            'active' => 1,
            'is_blacklist' => 0,
        ]);
    }

    #[Test]
    public function it_updates_existing_fras_on_rerun(): void
    {
        $atcPosition = $this->createAtcPosition(100);

        AtcPositionFra::create([
            'ivao_id' => 1,
            'atc_position_id' => $atcPosition->id,
            'atc_compose_position' => $atcPosition->compose_position,
            'ivao_user_id' => 999,
            'ivao_atc_position_id' => 100,
            'ivao_subcenter_id' => null,
            'start_time' => '08:00',
            'end_time' => '16:00',
            'monday' => true,
            'tuesday' => true,
            'wednesday' => true,
            'thursday' => true,
            'friday' => true,
            'saturday' => false,
            'sunday' => false,
            'date' => null,
            'min_atc' => 1,
            'active' => true,
            'is_blacklist' => false,
        ]);

        $updated = $this->fakeFra(1, 100);
        $updated['startTime'] = '09:00';
        $updated['minAtc'] = 2;

        $this->mock(Ivao::class)
            ->shouldReceive('allAtcPositionFras')
            ->once()
            ->andReturn([$updated]);

        $this->artisan('ivao:fetch-atc-position-fras')->assertSuccessful();

        $this->assertDatabaseCount('atc_position_fras', 1);
        $this->assertDatabaseHas('atc_position_fras', [
            'ivao_id' => 1,
            'start_time' => '09:00',
            'min_atc' => 2,
        ]);
    }

    #[Test]
    public function it_creates_missing_atc_position_from_embedded_atc_position(): void
    {
        $this->assertDatabaseCount('atc_positions', 0);

        $this->mock(Ivao::class)
            ->shouldReceive('allAtcPositionFras')
            ->once()
            ->andReturn([$this->fakeFra(1, 200)]);

        $this->artisan('ivao:fetch-atc-position-fras')->assertSuccessful();

        $this->assertDatabaseHas('atc_positions', [
            'ivao_id' => 200,
            'airport_id' => 'SKBO',
            'atc_callsign' => 'SKBO_TWR',
        ]);
        $this->assertDatabaseCount('atc_position_fras', 1);
    }

    #[Test]
    public function it_creates_missing_atc_position_from_embedded_subcenter(): void
    {
        $fra = $this->fakeFra(1, 0);
        $fra['atcPositionId'] = null;
        $fra['subcenterId'] = 300;
        $fra['atcPosition'] = null;
        $fra['subcenter'] = [
            'id' => 300,
            'centerId' => 'SKED',
            'atcCallsign' => 'SKED_CTR',
            'composePosition' => 'SKED_CTR',
            'middleIdentifier' => 'CTR',
            'position' => 'CTR',
        ];

        $this->mock(Ivao::class)
            ->shouldReceive('allAtcPositionFras')
            ->once()
            ->andReturn([$fra]);

        $this->artisan('ivao:fetch-atc-position-fras')->assertSuccessful();

        $this->assertDatabaseHas('atc_positions', [
            'ivao_id' => 300,
            'airport_id' => 'SKED',
            'atc_callsign' => 'SKED_CTR',
        ]);
        $this->assertDatabaseCount('atc_position_fras', 1);
    }

    #[Test]
    public function it_warns_and_skips_when_no_local_or_embedded_atc_position(): void
    {
        $fra = $this->fakeFra(1, 999);
        $fra['atcPosition'] = null;
        $fra['subcenter'] = null;

        $this->mock(Ivao::class)
            ->shouldReceive('allAtcPositionFras')
            ->once()
            ->andReturn([$fra]);

        $this->artisan('ivao:fetch-atc-position-fras')
            ->expectsOutput('Skipping FRA #1: no local or embedded ATC position found for IVAO position ID 999.')
            ->assertSuccessful();

        $this->assertDatabaseCount('atc_position_fras', 0);
    }

    #[Test]
    public function it_deletes_stale_fras_no_longer_returned_by_the_api(): void
    {
        $atcPosition = $this->createAtcPosition(100);

        AtcPositionFra::create([
            'ivao_id' => 99,
            'atc_position_id' => $atcPosition->id,
            'atc_compose_position' => $atcPosition->compose_position,
            'ivao_user_id' => 999,
            'ivao_atc_position_id' => 100,
            'ivao_subcenter_id' => null,
            'start_time' => '08:00',
            'end_time' => '16:00',
            'monday' => true,
            'tuesday' => true,
            'wednesday' => true,
            'thursday' => true,
            'friday' => true,
            'saturday' => false,
            'sunday' => false,
            'date' => null,
            'min_atc' => 1,
            'active' => true,
            'is_blacklist' => false,
        ]);

        $this->mock(Ivao::class)
            ->shouldReceive('allAtcPositionFras')
            ->once()
            ->andReturn([$this->fakeFra(1, 100)]);

        $this->artisan('ivao:fetch-atc-position-fras')
            ->expectsOutput('Removed 1 stale FRA(s) no longer returned by the API.')
            ->assertSuccessful();

        $this->assertDatabaseMissing('atc_position_fras', ['ivao_id' => 99]);
        $this->assertDatabaseHas('atc_position_fras', ['ivao_id' => 1]);
    }

    #[Test]
    public function it_allows_null_min_atc_for_user_specific_fras(): void
    {
        $this->createAtcPosition(100);

        $fra = $this->fakeFra(1, 100);
        $fra['minAtc'] = null;

        $this->mock(Ivao::class)
            ->shouldReceive('allAtcPositionFras')
            ->once()
            ->andReturn([$fra]);

        $this->artisan('ivao:fetch-atc-position-fras')->assertSuccessful();

        $this->assertDatabaseHas('atc_position_fras', [
            'ivao_id' => 1,
            'min_atc' => null,
        ]);
    }

    #[Test]
    public function it_returns_failure_when_the_api_returns_empty(): void
    {
        $this->mock(Ivao::class)
            ->shouldReceive('allAtcPositionFras')
            ->once()
            ->andReturn([]);

        $this->artisan('ivao:fetch-atc-position-fras')
            ->expectsOutput('Failed to fetch ATC position FRAs from IVAO.')
            ->assertFailed();

        $this->assertDatabaseCount('atc_position_fras', 0);
    }
}
