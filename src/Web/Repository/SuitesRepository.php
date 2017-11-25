<?php

namespace Phpbench\Reports\Repository;

use Elastica\Client;

class SuitesRepository
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
}
