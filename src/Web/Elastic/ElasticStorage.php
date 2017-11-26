<?php

namespace Phpbench\Reports\Elastic;

use Elasticsearch\Client;

class ElasticStorage
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function put(string $type, array $documents)
    {
        foreach ($documents as $id => $document) {
            $params = [
                'index' => 'phpbench_' . $type,
                'type' => 'doc',
                'id' => $id,
                'body' => $document
            ];

            $this->client->index($params);
        }
    }
}
