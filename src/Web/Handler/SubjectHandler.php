<?php

namespace Phpbench\Reports\Handler;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Twig\Environment;
use Pagerfanta\Pagerfanta;
use Phpbench\Reports\Repository\BenchmarkRepository;
use Phpbench\Reports\Repository\SubjectRepository;

class SubjectHandler
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var SubjectRepository
     */
    private $repository;

    public function __construct(Environment $twig, SubjectRepository $repository)
    {
        $this->twig = $twig;
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $class = $request->getAttribute('class');
        $subject = $request->getAttribute('name');
        $subjects = $this->repository->subjectAggregates($class, $subject);
        $response->getBody()->write($this->twig->render('subject.html.twig', [
            'aggregates' => $subjects,
            'benchmark' => $class,
            'subject' => $subject,
        ]));

        return $response;
    }
}
