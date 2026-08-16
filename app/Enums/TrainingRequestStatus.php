<?php

declare(strict_types=1);

namespace App\Enums;

enum TrainingRequestStatus: string
{
    case Pending = 'pending';
    case Scheduled = 'scheduled';
    case Cancelled = 'cancelled';
    case Completed = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Scheduled => 'Scheduled',
            self::Cancelled => 'Cancelled',
            self::Completed => 'Completed',
        };
    }
}
