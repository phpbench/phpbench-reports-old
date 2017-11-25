<?php

namespace Phpbench\Reports\Middleware;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class HandlerMiddleware
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $handler = $this->container->get($request->getAttribute(RouterMiddleware::REQUEST_HANDLER));

        return $handler->__invoke($request, $response);
    }
}
