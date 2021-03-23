<?php

namespace App\Application\DataTransformer\Transference;

use App\Domain\Model\User;

class NewTransferenceRequestInput
{
    /** @var User */
    private $payer;

    /** @var int */
    private $payeeId;

    /** @var float */
    private $amount;

    public function __construct(User $payerId, int $payeeId, float $amount)
    {
        $this->payer = $payerId;
        $this->payeeId = $payeeId;
        $this->amount = $amount;
    }

    public function getPayer(): User
    {
        return $this->payer;
    }

    public function getPayeeId(): int
    {
        return $this->payeeId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
