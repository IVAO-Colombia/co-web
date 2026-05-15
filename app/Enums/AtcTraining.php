<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Collection;

enum AtcTraining: string
{
    case As2As3Intro = 'as2_as3_intro';
    case AdcTheory1 = 'adc_theory_1';
    case AdcTheory2 = 'adc_theory_2';
    case AdcTheory3 = 'adc_theory_3';
    case AdcTheory4 = 'adc_theory_4';
    case AdcPractical = 'adc_practical';
    case ApcTheory1 = 'apc_theory_1';
    case ApcTheory2 = 'apc_theory_2';
    case ApcTheory3 = 'apc_theory_3';
    case ApcPractical = 'apc_practical';
    case AccTheory1 = 'acc_theory_1';
    case AccTheory2 = 'acc_theory_2';
    case AccPractical = 'acc_practical';

    public function label(): string
    {
        return match ($this) {
            self::As2As3Intro => 'AS2/AS3 - Introducción como ATC',
            self::AdcTheory1 => 'ADC - Entrenamiento Teórico parte 1',
            self::AdcTheory2 => 'ADC - Entrenamiento Teórico parte 2',
            self::AdcTheory3 => 'ADC - Entrenamiento Teórico parte 3',
            self::AdcTheory4 => 'ADC - Entrenamiento Teórico parte 4',
            self::AdcPractical => 'ADC - Entrenamiento Práctico',
            self::ApcTheory1 => 'APC - Entrenamiento Teórico parte 1',
            self::ApcTheory2 => 'APC - Entrenamiento Teórico parte 2',
            self::ApcTheory3 => 'APC - Entrenamiento Teórico parte 3',
            self::ApcPractical => 'APC - Entrenamiento Práctico',
            self::AccTheory1 => 'ACC - Entrenamiento Teórico parte 1',
            self::AccTheory2 => 'ACC - Entrenamiento Teórico parte 2',
            self::AccPractical => 'ACC - Entrenamiento Práctico',
        };
    }

    public function minimumAtcRating(): ATCRating
    {
        return match ($this) {
            self::As2As3Intro => ATCRating::AS1,
            self::AdcTheory1,
            self::AdcTheory2,
            self::AdcTheory3,
            self::AdcTheory4,
            self::AdcPractical => ATCRating::AS3,
            self::ApcTheory1,
            self::ApcTheory2,
            self::ApcTheory3,
            self::ApcPractical => ATCRating::ADC,
            self::AccTheory1,
            self::AccTheory2,
            self::AccPractical => ATCRating::APC,
        };
    }

    public function order(): int
    {
        return match ($this) {
            self::As2As3Intro => 1,
            self::AdcTheory1 => 2,
            self::AdcTheory2 => 3,
            self::AdcTheory3 => 4,
            self::AdcTheory4 => 5,
            self::AdcPractical => 6,
            self::ApcTheory1 => 8,
            self::ApcTheory2 => 9,
            self::ApcTheory3 => 10,
            self::ApcPractical => 11,
            self::AccTheory1 => 12,
            self::AccTheory2 => 13,
            self::AccPractical => 14,
        };
    }

    /**
     * @return Collection<int, mixed>
     */
    public static function forRating(?ATCRating $atcRating): Collection
    {
        return collect(AtcTraining::cases())
            ->filter(fn (AtcTraining $t): bool => $atcRating instanceof ATCRating && $atcRating->value >= $t->minimumAtcRating()->value)
            ->sortBy(fn (AtcTraining $t): int => $t->order())
            ->values();
    }
}
