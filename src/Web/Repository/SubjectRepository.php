<?php

namespace Phpbench\Reports\Repository;

use Elasticsearch\Client;
use Phpbench\Reports\Model\BenchmarkRow;
use Phpbench\Reports\Model\SubjectRow;

class SubjectRepository
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

    public function subjectRowsForBenchmarkClass(string $benchmarkClass)
    {
        return $this->subjectRowsFromResult($benchmarkClass, $this->client->search([
            'index' => self::INDEX,
            'body' => [
                'aggs' => [
                    'benchmark' => [
                        'filter' => [
                            'term' => [
                                'class.keyword' => $benchmarkClass,
                            ],
                        ],
                        'aggs' => [
                            'subjects' => [
                                'terms' => [
                                    'field' => 'name.keyword'
                                ],
                            ]
                        ]
                    ],
                ],
            ],
        ]));
    }

    private function subjectRowsFromResult(string $benchmarkClass, array $result)
    {
        $benchmarks = [];
        foreach ($result['aggregations']['benchmark']['subjects']['buckets'] as $bucket) {
            $benchmarks[] = new SubjectRow($benchmarkClass, $bucket['key'], $bucket['doc_count']);
        }

        return $benchmarks;
    }
}
