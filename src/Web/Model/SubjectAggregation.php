<?php

namespace Phpbench\Reports\Model;

use DateTimeImmutable;

class SubjectAggregation
{
    /**
     * @var string
     */
    private $benchmarkClass;

    /**
     * @var string
     */
    private $subjectName;

    /**
     * @var string
     */
    private $averageTime;

    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $iterations;

    /**
     * @var string
     */
    private $vcsBranch;

    /**
     * @var string
     */
    private $suite;

    public function __construct(
        DateTimeImmutable $date,
        string $suite,
        string $benchmarkClass,
        string $subjectName,
        string $averageTime,
        string $host,
        int $iterations,
        string $vcsBranch
    )
    {
        $this->benchmarkClass = $benchmarkClass;
        $this->subjectName = $subjectName;
        $this->averageTime = $averageTime;
        $this->date = $date;
        $this->host = $host;
        $this->iterations = $iterations;
        $this->vcsBranch = $vcsBranch;
        $this->suite = $suite;
    }

    public function benchmark(): string
    {
        return $this->benchmarkClass;
    }

    public function name(): string
    {
        return $this->subjectName;
    }

    public function averageTime(): string
    {
        return $this->averageTime;
    }

    public function date(): DateTimeImmutable
    {
        return $this->date;
    }

    public function host()
    {
        return $this->host;
    }

    public function iterations(): int
    {
        return $this->iterations;
    }

    public function vcsBranch(): string
    {
        return $this->vcsBranch;
    }

    public function suite(): string
    {
        return $this->suite;
    }
}
