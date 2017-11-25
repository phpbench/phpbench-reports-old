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
     * @var SuitesRepository
     */
    private $repository;

    public function __construct(Environment $twig, VariantRepository $repository)
    {
        $this->twig = $twig;
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write($this->twig->render('suites.html.twig', [
            'benchmarks' => $this->repository->benchmarks(50)
        ]));
        return $response;
    }
}
