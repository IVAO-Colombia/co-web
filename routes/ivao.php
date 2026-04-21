<?php

declare(strict_types=1);

use App\Services\Ivao\Ivao;

Route::get('ivao/airports/{icao}/atc-positions', function (string $icao) {
    return app(Ivao::class)->atcPositions($icao);
})->name('ivao.airports.atc-positions');
