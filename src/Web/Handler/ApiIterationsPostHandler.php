<?php

namespace Phpbench\Reports\Handler;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Phpbench\Reports\Elastic\ElasticStorage;

class ApiIterationsPostHandler
{
    /**
     * @var ElasticStorage
     */
    private $storage;

    public function __construct(ElasticStorage $storage)
    {
        $this->storage = $storage;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->storage->put('iteration', (array) json_decode($request->getBody()->getContents(), true));
        $response->getBody()->write(json_encode([]));
        $response = $response->withHeader('Content-Type', 'application/json');

        return $response;
    }
}
