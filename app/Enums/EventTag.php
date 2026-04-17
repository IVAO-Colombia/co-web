<?php

declare(strict_types=1);

namespace App\Enums;

enum EventTag: string
{
    case VFR = 'vfr';
    case IFR = 'ifr';
    case CrossCountry = 'cross-country';
    case Division = 'division';
    case Hq = 'hq';

    public function label(): string
    {
        return match ($this) {
            self::VFR => 'VFR',
            self::IFR => 'IFR',
            self::CrossCountry => 'Cross Country',
            self::Division => 'Division',
            self::Hq => 'HQ',
        };
    }
}
