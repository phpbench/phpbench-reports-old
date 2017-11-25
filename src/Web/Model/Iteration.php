<?php

namespace Phpbench\Reports\Model;

class Iteration
{
    /**
     * @var int
     */
    private $index;

    /**
     * @var array
     */
    private $results;

    public function __construct(int $index, array $results)
    {
        $this->index = $index;
        $this->results = $results;
    }

    public function index(): int
    {
        return $this->index;
    }

    public function metric(string $type, string $metric)
    {
        if (!isset($this->results[$type]['metrics'][$metric])) {
            return null;
        }

        return $this->results[$type]['metrics'][$metric];
    }
}
