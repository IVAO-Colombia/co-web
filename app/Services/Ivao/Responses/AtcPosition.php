<?php

declare(strict_types=1);

namespace App\Services\Ivao\Responses;

use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
class AtcPosition
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public int $id,
        public string $airportId,
        public string $atcCallsign,
        public string $composePosition,
        public ?string $middleIdentifier,
        public string $position,
        public int $order,
        public float $frequency,
        public ?float $radarRange
    ) {
        //
    }
}
