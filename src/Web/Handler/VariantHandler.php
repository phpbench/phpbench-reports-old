<?php

namespace Phpbench\Reports\Handler;

use Twig\Environment;
use Phpbench\Reports\Repository\IterationRepository;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class VariantHandler
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var IterationRepository
     */
    private $repository;

    public function __construct(Environment $twig, IterationRepository $repository)
    {
        $this->twig = $twig;
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $class = $request->getAttribute('class');
        $subject = $request->getAttribute('name');
        $suite = $request->getAttribute('suite');

        $iterations = $this->repository->iterationsFor($suite, $class, $subject);

        $response->getBody()->write($this->twig->render('variant.html.twig', [
            'iterations' => $iterations,
            'benchmark' => $class,
            'subject' => $subject,
            'suite' => $suite,
        ]));

        return $response;
    }
}
