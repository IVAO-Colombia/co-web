<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class AtcReauthRequiredException extends Exception
{
    public function __construct()
    {
        parent::__construct('IVAO session expired. Please log in again.');
    }
}
