<?php

namespace Phpbench\Reports\Handler;

use Elasticsearch\Client;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Phpbench\Reports\Repository\SuiteRepository;
use Phpbench\Reports\Elastic\ElasticStorage;

class ApiSuitePostHandler
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
        $this->storage->put('variant', (array) json_decode($request->getBody()->getContents(), true));
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write('[]');

        return $response;
    }
}
