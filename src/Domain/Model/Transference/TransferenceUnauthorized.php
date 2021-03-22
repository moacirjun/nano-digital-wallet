<?php

namespace App\Domain\Model\Transference;

use RuntimeException;

class TransferenceUnauthorized extends RuntimeException
{
    public function __construct(string $reason)
    {
        parent::__construct($reason, 401);
    }

}