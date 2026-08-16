<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Collection;

enum PilotTraining: string
{
    case Fs2Fs3Intro = 'fs2_fs3_intro';
    case PpTheory1 = 'pp_theory_1';
    case PpTheory2 = 'pp_theory_2';
    case PpTheory3 = 'pp_theory_3';
    case PpTheory4 = 'pp_theory_4';
    case PpTheory5 = 'pp_theory_5';
    case PpPractical = 'pp_practical';
    case SppTheory1 = 'spp_theory_1';
    case SppTheory2 = 'spp_theory_2';
    case SppTheory3 = 'spp_theory_3';
    case SppTheory4 = 'spp_theory_4';
    case SppPractical = 'spp_practical';
    case CpTheory1 = 'cp_theory_1';
    case CpTheory2 = 'cp_theory_2';
    case CpTheory3 = 'cp_theory_3';
    case CpTheory4 = 'cp_theory_4';
    case CpPractical = 'cp_practical';

    public function label(): string
    {
        return match ($this) {
            self::Fs2Fs3Intro => 'FS2/FS3 - Introducción como Piloto',
            self::PpTheory1 => 'PP - Entrenamiento Teórico Parte 1',
            self::PpTheory2 => 'PP - Entrenamiento Teórico Parte 2',
            self::PpTheory3 => 'PP - Entrenamiento Teórico Parte 3',
            self::PpTheory4 => 'PP - Entrenamiento Teórico Parte 4',
            self::PpTheory5 => 'PP - Entrenamiento Teórico Parte 5',
            self::PpPractical => 'PP - Entrenamiento Práctico',
            self::SppTheory1 => 'SPP - Entrenamiento Teórico parte 1',
            self::SppTheory2 => 'SPP - Entrenamiento Teórico parte 2',
            self::SppTheory3 => 'SPP - Entrenamiento Teórico parte 3',
            self::SppTheory4 => 'SPP - Entrenamiento Teórico parte 4',
            self::SppPractical => 'SPP - Entrenamiento Práctico',
            self::CpTheory1 => 'CP - Entrenamiento Teórico parte 1',
            self::CpTheory2 => 'CP - Entrenamiento Teórico parte 2',
            self::CpTheory3 => 'CP - Entrenamiento Teórico parte 3',
            self::CpTheory4 => 'CP - Entrenamiento Teórico parte 4',
            self::CpPractical => 'CP - Entrenamiento Práctico',
        };
    }

    public function minimumPilotRating(): PilotRating
    {
        return match ($this) {
            self::Fs2Fs3Intro => PilotRating::FS1,
            self::PpTheory1,
            self::PpTheory2,
            self::PpTheory3,
            self::PpTheory4,
            self::PpTheory5,
            self::PpPractical => PilotRating::FS3,
            self::SppTheory1,
            self::SppTheory2,
            self::SppTheory3,
            self::SppTheory4,
            self::SppPractical => PilotRating::PP,
            self::CpTheory1,
            self::CpTheory2,
            self::CpTheory3,
            self::CpTheory4,
            self::CpPractical => PilotRating::SPP,
        };
    }

    public function order(): int
    {
        return match ($this) {
            self::Fs2Fs3Intro => 1,
            self::PpTheory1 => 2,
            self::PpTheory2 => 3,
            self::PpTheory3 => 4,
            self::PpTheory4 => 5,
            self::PpTheory5 => 6,
            self::PpPractical => 7,
            self::SppTheory1 => 8,
            self::SppTheory2 => 9,
            self::SppTheory3 => 10,
            self::SppTheory4 => 11,
            self::SppPractical => 12,
            self::CpTheory1 => 13,
            self::CpTheory2 => 14,
            self::CpTheory3 => 15,
            self::CpTheory4 => 16,
            self::CpPractical => 17,
        };
    }

    /**
     * @return Collection<int, mixed>
     */
    public static function forRating(?PilotRating $pilotRating): Collection
    {
        return collect(PilotTraining::cases())
            ->filter(fn (PilotTraining $t): bool => $pilotRating instanceof PilotRating && $pilotRating->value >= $t->minimumPilotRating()->value)
            ->sortBy(fn (PilotTraining $t): int => $t->order());
    }
}
