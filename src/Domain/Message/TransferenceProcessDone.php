<?php

namespace App\Domain\Message;

use App\Entity\Transference;

class TransferenceProcessDone
{
    /** @var Transference */
    private $transference;

    public function __construct(Transference $transference)
    {
        $this->transference = $transference;
    }

    public function getTransference(): Transference
    {
        return $this->transference;
    }
}