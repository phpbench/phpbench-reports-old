<?php

namespace Phpbench\Reports\Repository;

use Elasticsearch\Client;
use Phpbench\Reports\Model\Iteration;
use Phpbench\Reports\Model\Iterations;

class IterationRepository
{
    const INDEX = 'phpbench_iteration';

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function iterationsFor(string $suite, string $benchmark, string $subject)
    {
        return $this->itreationsFromResult($this->client->search([
            'index' => self::INDEX,
            'size' => 100,
            'body' => [
                'sort' =>  [
                    'index' => 'ASC',
                ],
                'query'=> [
                    'bool' => [
                        'must' => [ 
                            [
                                'term' => [
                                    'class.keyword' => $benchmark,
                                ],
                            ],
                            [
                                'term' => [
                                    'subject.keyword' => $subject,
                                ],
                            ],
                            [
                                'term' => [
                                    'suite.keyword' => $suite,
                                ],
                            ],
                        ],
                    ],
                ]
            ]
        ]));
    }

    private function itreationsFromResult(array $result)
    {
        $iterations = [];

        foreach ($result['hits']['hits'] as $hit) {
            $data = $hit['_source'];
            $iterations[] = new Iteration($data['index'], $data['results']);
        }

        return Iterations::fromIterations($iterations);
    }
}
