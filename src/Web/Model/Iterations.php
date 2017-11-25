<?php

namespace Phpbench\Reports\Model;

use Phpbench\Reports\Util\Statistics;

final class Iterations implements \IteratorAggregate
{
    private $iterations = [];

    private function __construct($iterations)
    {
        foreach ($iterations as $item) {
            $this->add($item);
        }
    }

    public static function fromIterations(array $iterations): Iterations
    {
         return new self($iterations);
    }

    public function metrics(string $type, string $metric): array
    {
        return array_map(function (Iteration $iteration) use ($type, $metric) {
            return $iteration->metric($type, $metric);
        }, $this->iterations);
    }

    public function standardDeviation(string $type, string $metric): float
    {
        $metrics = $this->metrics($type, $metric);
        return Statistics::stdev($metrics);
    }

    public function relativeStandardDeviation(string $type, string $metric): float
    {
        $metrics = $this->metrics($type, $metric);
        return Statistics::rstdev($metrics);
    }

    public function histogram(string $type, string $metric, int $steps = 25): array
    {
        $metrics = $this->metrics($type, $metric);
        return Statistics::histogram($metrics, $steps);
    }

    public function indexes(): array
    {
        return array_map(function (Iteration $iteration) {
            return $iteration->index();
        }, $this->iterations);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->iterations);
    }

    private function add(Iteration $item)
    {
        $this->iterations[] = $item;
    }
}
