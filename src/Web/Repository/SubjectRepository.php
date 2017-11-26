<?php

namespace Phpbench\Reports\Repository;

use Elasticsearch\Client;
use Phpbench\Reports\Model\BenchmarkRow;
use Phpbench\Reports\Model\SubjectRow;
use Phpbench\Reports\Model\SubjectAggregation;
use DateTimeImmutable;
use Phpbench\Reports\Model\SubjectAggregations;

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
            'size' => 0,
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
                                    'field' => 'subject.keyword'
                                ],
                            ]
                        ]
                    ],
                ],
            ],
        ]));
    }

    public function subjectAggregates(string $benchmarkClass, string $subjectName)
    {
        return $this->subjectAggregationsFromResult($benchmarkClass, $subjectName, $this->client->search([
            'index' => self::INDEX,
            'body' => [
                'aggs' => [
                    'benchmark' => [
                        'filter' => [
                            'bool' => [
                                'must' => [ 
                                    [
                                        'term' => [
                                            'class.keyword' => $benchmarkClass,
                                        ],
                                    ],
                                    [
                                        'term' => [
                                            'subject.keyword' => $subjectName,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'aggs' => [
                            'subjects' => [
                                'terms' => [
                                    'field' => 'suite.keyword'
                                ],
                                'aggs' => [
                                    'average' => [
                                        'avg' => [
                                            'field' => 'stats.mean'
                                        ],
                                    ],
                                    'date' => [
                                        'terms' => [
                                            'field' => 'date'
                                        ],
                                    ],
                                    'host' => [
                                        'terms' => [
                                            'field' => 'env.uname.host.keyword'
                                        ],
                                    ],
                                    'branch' => [
                                        'terms' => [
                                            'field' => 'env.vcs.branch.keyword'
                                        ],
                                    ],
                                    'iterations' => [
                                        'sum' => [
                                            'field' => 'nb_iterations'
                                        ],
                                    ],
                                ],
                            ],
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

    private function subjectAggregationsFromResult(string $benchmarkClass, string $subjectName, array $result): SubjectAggregations
    {
        $aggregations = [];
        foreach ($result['aggregations']['benchmark']['subjects']['buckets'] as $bucket) {
            $aggregations[] = new SubjectAggregation(
                new DateTimeImmutable($bucket['date']['buckets'][0]['key_as_string']),
                $bucket['key'],
                $benchmarkClass,
                $subjectName,
                $bucket['average']['value'],
                $bucket['host']['buckets'][0]['key'],
                $bucket['iterations']['value'],
                $bucket['branch']['buckets'][0]['key']
            );
        }

        return SubjectAggregations::fromSubjectAggregations($aggregations);
    }
}
