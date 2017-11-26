<?php

namespace Phpbench\Reports\Handler;

use Elasticsearch\Client;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Phpbench\Reports\Repository\SuiteRepository;

class ApiSuitePostHandler
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write(json_encode([]));
        $response = $response->withHeader('Content-Type', 'application/json');

        return $response;
    }
}
