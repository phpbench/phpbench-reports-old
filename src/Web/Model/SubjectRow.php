<?php

namespace Phpbench\Reports\Model;

class SubjectRow
{
    /**
     * @var string
     */
    private $benchmark;

    /**
     * @var string
     */
    private $name;

    /**
     * @var int
     */
    private $count;

    public function __construct(string $benchmark, string $name, int $count)
    {
        $this->benchmark = $benchmark;
        $this->name = $name;
        $this->count = $count;
    }

    public function benchmark(): string
    {
        return $this->benchmark;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function count()
    {
        return $this->count;
    }
}
