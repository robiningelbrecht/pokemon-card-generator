<?php

namespace App\Tests;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Uri;

trait ProvideHttpRequest
{
    public function buildRequest(
        string $method,
        string $host,
        string $uriScheme = 'https',
        array $headers = []): ServerRequestInterface
    {
        $streamFactory = new StreamFactory();
        $header = new Headers();
        $header->setHeader('HTTP_ACCEPT', 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8');
        foreach ($headers as $key => $value) {
            $header->setHeader($key, $value);
        }

        return new Request(
            $method,
            new Uri($uriScheme, $host),
            $header,
            [],
            [],
            $streamFactory->createStream()
        );
    }
}
