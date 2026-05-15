<?php

declare(strict_types=1);

namespace App\Enums;

enum TrainingRequestType: string
{
    case ATC = 'atc';
    case Pilot = 'pilot';

    public function label(): string
    {
        return match ($this) {
            self::ATC => 'ATC',
            self::Pilot => 'Pilot',
        };
    }
}
