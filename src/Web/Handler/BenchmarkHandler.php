<?php

namespace Phpbench\Reports\Handler;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Pagerfanta\Pagerfanta;
use Phpbench\Reports\Repository\BenchmarkRepository;
use Phpbench\Reports\Repository\SubjectRepository;

class BenchmarkHandler
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var VariantRepository
     */
    private $repository;

    public function __construct(Environment $twig, SubjectRepository $repository)
    {
        $this->twig = $twig;
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $subjects = $this->repository->subjectRowsForBenchmarkClass($request->getAttribute('class'));
        $response->getBody()->write($this->twig->render('benchmark.html.twig', [
            'subjects' => $subjects
        ]));

        return $response;
    }
}
