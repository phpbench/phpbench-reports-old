<?php

namespace Phpbench\Reports\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;

class JsonErrorMiddleware
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        try {
            return $next($request, $response);
        } catch (Exception $exception) {
            if ($request->hasHeader('content-type') && $request->getHeader('content-type')[0] !== 'application/json') {
                throw $exception;
            }
            $response = $response->withStatus('500');

            $response->getBody()->write(json_encode([
                'exception' => get_class($exception),
                'message' => $exception->getMessage(),
            ]));

            return $response;
        }
    }
}
