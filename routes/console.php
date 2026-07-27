<?php

declare(strict_types=1);

use App\Console\Commands\IvaoFetchAtcPositionFras;
use App\Console\Commands\IvaoFetchAtcPositions;
use App\Console\Commands\ProcessPilotSlotConfirmations;
use Illuminate\Support\Facades\Schedule;

Schedule::command(IvaoFetchAtcPositions::class)->dailyAt('01:00');
Schedule::command(IvaoFetchAtcPositionFras::class)->dailyAt('01:30');
// 05:00 UTC-5 (Colombia time)
Schedule::command(ProcessPilotSlotConfirmations::class)->dailyAt('10:00');
