<?php

declare(strict_types=1);

use App\Http\Controllers\Ivao\AtcPositionsController;

Route::get('ivao/airports/{icao}/atc-positions', AtcPositionsController::class)->name('ivao.airports.atc-positions');
