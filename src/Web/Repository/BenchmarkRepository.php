<?php

namespace Phpbench\Reports\Repository;

use Elasticsearch\Client;
use Phpbench\Reports\Model\BenchmarkRow;

class BenchmarkRepository
{
    const INDEX = 'phpbench_variant';

    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function benchmarks(string $limit)
    {
        return $this->benchmarksFromResult($this->client->search([
            'index' => self::INDEX,
            'size' => 0,
            'body' => [
                'aggs' => [
                    'benchmarks' => [
                        'terms' => [
                            'field' => 'benchmark.keyword'
                        ],
                    ],
                ],
            ],
        ]));
    }

    public function subjects(string $benchmarkClass)
    {
        return [];
    }

    private function benchmarksFromResult(array $result)
    {
        $benchmarks = [];
        foreach ($result['aggregations']['benchmarks']['buckets'] as $bucket) {
            $benchmarks[] = new BenchmarkRow($bucket['key'], $bucket['doc_count']);
        }

        return $benchmarks;
    }
}
