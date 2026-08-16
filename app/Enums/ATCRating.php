<?php

declare(strict_types=1);

namespace App\Enums;

enum ATCRating: int
{
    case AS1 = 2;
    case AS2 = 3;
    case AS3 = 4;
    case ADC = 5;
    case APC = 6;
    case ACC = 7;
    case SEC = 8;
    case SAI = 9;
    case CAI = 10;

    public function label(): string
    {
        return match ($this) {
            self::AS1 => 'ATC Applicant',
            self::AS2 => 'ATC Trainee',
            self::AS3 => 'Advanced ATC Trainee',
            self::ADC => 'Aerodrome Controller',
            self::APC => 'Approach Controller',
            self::ACC => 'Center Controller',
            self::SEC => 'Senior Controller',
            self::SAI => 'Senior ATC Instructor',
            self::CAI => 'Chief ATC Instructor',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::AS1 => 'Rating given when applying for membership.',
            self::AS2 => 'Rating automatically achieved after 10 hours online as a controller.',
            self::AS3 => 'Rating requires at least 25 hours online as a controller and a successful theoretical Aurora test.',
            self::ADC => 'Rating requires at least 50 hours online as a controller and a successful theoretical and practical test.',
            self::APC => 'Rating requires at least 100 hours online as a controller and a successful theoretical and practical test.',
            self::ACC => 'Rating requires at least 200 hours online as a controller and a successful theoretical and practical test.',
            self::SEC => 'Rating requires at least 1000 hours online as a controller, a successful theoretical and practical test, as well as the Senior Private Pilot rating. Additional requirements apply, please check the SEC Briefing Guide.',
            self::SAI => 'Rating is issued to selected members of the Training Staff and Senior Training Advisors. Given by the Training Director or Training Assistant Director.',
            self::CAI => 'Rating for the IVAO Training Director & Assistant Director. Given by BoG / Executive on appointment.',
        };
    }

    public function imageUrl(): string
    {
        return "https://ivao.aero/data/images/ratings/atc/{$this->value}.gif";
    }
}
