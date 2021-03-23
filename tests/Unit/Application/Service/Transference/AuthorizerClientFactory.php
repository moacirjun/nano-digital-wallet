<?php

namespace App\Tests\Unit\Application\Service\Transference;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

class AuthorizerClientFactory
{
    public static function makeSuccess() : Client
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Type' => 'application/json'], '{"message": "Autorizado"}'),
        ]);

        $handler = HandlerStack::create($mock);

        return new Client(['handler' => $handler]);
    }

    public static function makeError() : Client
    {
        $mock = new MockHandler([
            new RequestException("Unexpected error.", new Request('POST', '/')),
        ]);

        $handler = HandlerStack::create($mock);

        return new Client(['handler' => $handler]);
    }
}
