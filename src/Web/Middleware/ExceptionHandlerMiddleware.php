<?php

namespace Phpbench\Reports\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Phpbench\Reports\Security\AccessDeniedException;

class ExceptionHandlerMiddleware
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        try {
            return $next($request, $response);
        } catch (AccessDeniedException $e) {
            $response->getBody()->write('Denied! ' . $e->getMessage());
            return $response->withStatus(403);
        }
    }
}
