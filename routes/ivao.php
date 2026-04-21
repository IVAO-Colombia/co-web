<?php

declare(strict_types=1);

use App\Services\Ivao\Ivao;

Route::get('ivao/airports/{icao}/atc-positions', fn (string $icao) => app(Ivao::class)->atcPositions($icao))->name('ivao.airports.atc-positions');
