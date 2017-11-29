<?php

namespace Phpbench\Reports\Model;

use Phpbench\Reports\Util\Statistics;
use MathPHP\Statistics\KernelDensityEstimation;
use MathPHP\Functions\Map\Single;
use MathPHP\Statistics\Average;

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

    public function mean(string $type, string $metric): int
    {
        $metrics = $this->metrics($type, $metric);
        return Statistics::mean($metrics);
    }

    public function min(string $type, string $metric): int
    {
        $metrics = $this->metrics($type, $metric);
        return min($metrics);
    }

    public function max(string $type, string $metric): int
    {
        $metrics = $this->metrics($type, $metric);
        return max($metrics);
    }

    public function relativeStandardDeviation(string $type, string $metric): float
    {
        $metrics = $this->metrics($type, $metric);
        return Statistics::rstdev($metrics);
    }

    public function mode(string $type, string $metric)
    {
        $metrics = $this->metrics($type, $metric);
        return Statistics::kdeMode($metrics);
    }

    public function densityEstimation(string $type, string $metric, int $steps = 25)
    {
        $values = $this->metrics($type, $metric);
        $kde = new KernelDensityEstimation($values);

        $min = min($values);
        $max = max($values);

        $step = ($max - $min) / $steps;

        $points = [];
        for ($i = $min; $i <= $max; $i+= $step) {
            $points[] = $kde->evaluate($i);
        }

        return $points;
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
