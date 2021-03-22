<?php


namespace App\Domain\Model\Transference;

class ExternalAuthorizerResponse
{
    /** @var string */
    private $response;

    /** @var string */
    private $failureReason;

    public function __construct(string $response, string $failureReason)
    {
        $this->response = $response;
        $this->failureReason = $failureReason;
    }

    public function getResponse() : string
    {
        return $this->response ?? '';
    }

    public function getFailureReason() : string
    {
        return $this->failureReason ?? '';
    }

    public function isAuthorized() : bool
    {
        return $this->response === 'Autorizado';
    }
}