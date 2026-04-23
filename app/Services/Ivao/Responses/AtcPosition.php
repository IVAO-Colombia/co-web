<?php

declare(strict_types=1);

namespace App\Services\Ivao\Responses;

class AtcPosition
{
    public function __construct(
        public int $id,
        public string $airportId,
        public string $atcCallsign,
        public string $composePosition,
        public ?string $middleIdentifier,
        public string $position,
        public int $order,
        public float $frequency,
        public ?int $radarRange
    ) {
        //
    }
}
