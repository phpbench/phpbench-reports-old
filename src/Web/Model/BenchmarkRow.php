<?php

namespace Phpbench\Reports\Model;

class BenchmarkRow
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $count;


    public function __construct(string $name, int $count)
    {
        $this->name = $name;
        $this->count = $count;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function count(): int
    {
        return $this->count;
    }
}
