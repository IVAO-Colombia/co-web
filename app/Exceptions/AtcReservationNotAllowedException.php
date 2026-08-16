<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class AtcReservationNotAllowedException extends Exception
{
    public function __construct(string $reason = '')
    {
        parent::__construct($reason ?: 'You are not allowed to reserve this ATC slot.');
    }
}
