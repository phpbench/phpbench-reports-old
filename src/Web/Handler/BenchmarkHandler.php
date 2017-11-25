<?php

namespace Phpbench\Reports\Handler;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Pagerfanta\Pagerfanta;
use Phpbench\Reports\Repository\VariantRepository;

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

    public function __construct(Environment $twig, VariantRepository $repository)
    {
        $this->twig = $twig;
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $subjects = $this->repository->subjects($request->getAttribute('class'));
        $response->getBody()->write($this->twig->render('benchmark.html.twig', [
            'benchmarks' => $subjects
        ]));

        return $response;
    }
}