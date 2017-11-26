<?php

namespace Phpbench\Reports\Handler;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class ApiIterationsPostHandler
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response->getBody()->write(json_encode([]));
        $response = $response->withHeader('Content-Type', 'application/json');

        return $response;
    }
}
