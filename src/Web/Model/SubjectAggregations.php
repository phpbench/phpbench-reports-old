<?php

namespace Phpbench\Reports\Model;

use Phpbench\Reports\Model\SubjectAggregation;

final class SubjectAggregations implements \IteratorAggregate
{
    private $subjectAggregations = [];

    private function __construct($subjectAggregations)
    {
        foreach ($subjectAggregations as $item) {
            $this->add($item);
        }
    }

    public static function fromSubjectAggregations(array $subjectAggregations): SubjectAggregations
    {
         return new self($subjectAggregations);
    }

    public function dates(): array
    {
        return array_map(function (SubjectAggregation $aggregation) {
            return $aggregation->date()->format('Y-m-d H:i:s');
        }, $this->subjectAggregations);
    }

    public function averageTimes(): array
    {
        return array_map(function (SubjectAggregation $aggregation) {
            return $aggregation->averageTime();
        }, $this->subjectAggregations);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->subjectAggregations);
    }

    private function add(SubjectAggregation $item)
    {
        $this->subjectAggregations[] = $item;
    }
}
