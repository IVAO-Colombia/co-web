<?php

declare(strict_types=1);

use App\Console\Commands\IvaoFetchAtcPositionFras;
use App\Console\Commands\IvaoFetchAtcPositions;
use Illuminate\Support\Facades\Schedule;

Schedule::command(IvaoFetchAtcPositions::class)->dailyAt('01:00');
Schedule::command(IvaoFetchAtcPositionFras::class)->dailyAt('01:30');
