<?php

declare(strict_types=1);

namespace App\Enums;

enum PilotRating: int
{
    case FS1 = 2;
    case FS2 = 3;
    case FS3 = 4;
    case PP = 5;
    case SPP = 6;
    case CP = 7;
    case ATP = 8;
    case SFI = 9;
    case CFI = 10;

    public function label(): string
    {
        return match ($this) {
            self::FS1 => 'Basic Flight Student',
            self::FS2 => 'Flight Student',
            self::FS3 => 'Advanced Flight Student',
            self::PP => 'Private Pilot',
            self::SPP => 'Senior Private Pilot',
            self::CP => 'Commercial Pilot',
            self::ATP => 'Airline Transport Pilot',
            self::SFI => 'Senior Flight Instructor',
            self::CFI => 'Chief Flight Instructor',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::FS1 => 'Rating given when applying for membership.',
            self::FS2 => 'Rating automatically achieved after 10 hours online as a pilot.',
            self::FS3 => 'Rating requires at least 25 hours online as a pilot and a successful theoretical Altitude test.',
            self::PP => 'Rating requires at least 50 hours online as a pilot and a successful theoretical and practical test.',
            self::SPP => 'Rating requires at least 100 hours online as a pilot and a successful theoretical and practical test.',
            self::CP => 'Rating requires at least 200 hours online as a pilot and a successful theoretical and practical test.',
            self::ATP => 'Rating requires at least 750 hours online as a pilot and a successful theoretical and practical test.',
            self::SFI => 'Rating is issued to selected members of the Training Staff and Senior Training Advisors. Given by the Training Director or Training Assistant Director.',
            self::CFI => 'Rating for the IVAO Training Director & Assistant Director. Given by BoG / Executive on appointment.',
        };
    }

    public function imageUrl(): string
    {
        return "https://ivao.aero/data/images/ratings/pilot/{$this->value}.gif";
    }
}
