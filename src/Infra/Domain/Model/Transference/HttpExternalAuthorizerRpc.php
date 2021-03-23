<?php

namespace App\Infra\Domain\Model\Transference;

use App\Domain\Model\Transference\ExternalAuthorizerResponse;
use App\Domain\Model\Transference\ExternalAuthorizerRpc;
use App\Domain\Model\Transference;
use App\Infra\Libs\BasicHttpClient\BasicHttpClient;

class HttpExternalAuthorizerRpc extends BasicHttpClient implements ExternalAuthorizerRpc
{
    public function checkTransferenceAuthorization(Transference $transference) : ExternalAuthorizerResponse
    {
        [$message, $failureReason] = $this->request();

        return new ExternalAuthorizerResponse($message, $failureReason);
    }
}
