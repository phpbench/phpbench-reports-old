<?php

namespace Phpbench\Reports\Handler;

use Elasticsearch\Client;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class SuitePostHandler
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, closure $next)
    {
        $this->client->params
    }
}
