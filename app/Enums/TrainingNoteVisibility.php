<?php

declare(strict_types=1);

namespace App\Enums;

enum TrainingNoteVisibility: string
{
    case PublicNote = 'public';
    case InternalNote = 'internal';

    /**
     * The training request column the notes of this visibility are stored in.
     */
    public function column(): string
    {
        return match ($this) {
            self::PublicNote => 'public_observations',
            self::InternalNote => 'internal_observations',
        };
    }
}
