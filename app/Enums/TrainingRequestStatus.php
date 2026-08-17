<?php

declare(strict_types=1);

namespace App\Enums;

enum TrainingRequestStatus: string
{
    case PENDING = 'pending';
    case SCHEDULED = 'scheduled';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::SCHEDULED => 'Scheduled',
            self::CANCELLED => 'Cancelled',
            self::COMPLETED => 'Completed',
        };
    }

    /**
     * Final statuses close the request: the trainer and the schedule can no
     * longer be changed, only notes and the status itself.
     */
    public function isFinal(): bool
    {
        return match ($this) {
            self::CANCELLED, self::COMPLETED => true,
            self::PENDING, self::SCHEDULED => false,
        };
    }
}
