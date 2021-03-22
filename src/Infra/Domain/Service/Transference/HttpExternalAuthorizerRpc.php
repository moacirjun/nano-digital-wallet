<?php

namespace App\Infra\Domain\Service\Transference;

use App\Domain\Model\Transference\ExternalAuthorizerResponse;
use App\Domain\Model\Transference\ExternalAuthorizerRpc;
use App\Domain\Model\Transference\UnexpectedResponseFromAuthorizer;
use App\Entity\Transference;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HttpExternalAuthorizerRpc implements ExternalAuthorizerRpc
{
    /** @var Client */
    private $client;

    /** @var string */
    private $uri;

    public function __construct(string $uri, ?Client $client)
    {
        $this->uri = $uri;
        $this->client = $client ?? $this->defaultClient();
    }

    public function checkTransferenceAuthorization(Transference $transference) : ExternalAuthorizerResponse
    {
        [$message, $failureReason] = $this->request();

        return new ExternalAuthorizerResponse($message, $failureReason);
    }

    private function request(): array
    {
        try {
            $response = $this->client->get($this->uri);
        } catch (GuzzleException $e) {
            return [
                'error',
                $e->getMessage(),
            ];
        }

        $response->getBody()->rewind();
        $responseBody = $response->getBody()->getContents();
        $responseAsAssocArray = json_decode($responseBody, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new UnexpectedResponseFromAuthorizer($responseBody);
        }

        if (
            !isset($responseAsAssocArray['message'])
            || !is_string($responseAsAssocArray['message'])
            || strlen(trim($responseAsAssocArray['message'])) === 0
        ) {
            throw new UnexpectedResponseFromAuthorizer($responseBody);
        }

        return [
            $responseAsAssocArray['message'],
            ''
        ];
    }

    private function defaultClient()
    {
        return new Client([
            'connect_timeout' => 6,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }
}