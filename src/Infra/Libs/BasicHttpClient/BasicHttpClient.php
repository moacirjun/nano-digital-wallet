<?php

namespace App\Infra\Libs\BasicHttpClient;

use App\Domain\Model\Transference\UnexpectedResponseFromAuthorizer;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

abstract class BasicHttpClient
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

    protected function defaultClient()
    {
        return new Client([
            'connect_timeout' => 6,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * @throws GuzzleException
     */
    protected function getJsonBodyFromUri() : string
    {
        $response = $this->client->get($this->uri);

        $response->getBody()->rewind();
        return $response->getBody()->getContents();
    }

    /**
     * @return array|string[]
     */
    protected function request(): array
    {
        try {
            $responseBody = $this->getJsonBodyFromUri();
        } catch (GuzzleException $e) {
            return [
                'error',
                $e->getMessage(),
            ];
        }

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
}
