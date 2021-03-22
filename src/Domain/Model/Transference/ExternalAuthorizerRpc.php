<?php

namespace App\Domain\Model\Transference;

use App\Entity\Transference;

interface ExternalAuthorizerRpc
{
    public function checkTransferenceAuthorization(Transference $transference) : ExternalAuthorizerResponse;
}