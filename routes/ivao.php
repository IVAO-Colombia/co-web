<?php

declare(strict_types=1);

use App\Http\Controllers\Ivao\AtcPositionsController;
use App\Http\Controllers\Landing\WhazzupController;

Route::get('ivao/airports/{icao}/atc-positions', AtcPositionsController::class)
    ->middleware('auth')
    ->name('ivao.airports.atc-positions');
Route::get('ivao/whazzup', WhazzupController::class)
    ->middleware('throttle:30,1')
    ->name('ivao.whazzup');
