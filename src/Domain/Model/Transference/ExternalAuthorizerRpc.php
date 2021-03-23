<?php

namespace App\Domain\Model\Transference;

use App\Domain\Model\Transference;

interface ExternalAuthorizerRpc
{
    public function checkTransferenceAuthorization(Transference $transference) : ExternalAuthorizerResponse;
}
