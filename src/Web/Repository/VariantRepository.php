<?php

namespace Phpbench\Reports\Repository;

use Elasticsearch\Client;
use Phpbench\Reports\Model\BenchmarkRow;

class VariantRepository
{
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
            'index' => 'phpbench_variant',
            'body' => [
                'aggs' => [
                    'benchmarks' => [
                        'terms' => [
                            'field' => 'class.keyword'
                        ],
                    ],
                ],
            ],
        ]));
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
