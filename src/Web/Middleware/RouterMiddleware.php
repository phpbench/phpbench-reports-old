<?php

namespace Phpbench\Reports\Middleware;

use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;
use Aura\Router\Map;
use Aura\Router\Matcher;
use Psr\Http\Message\ServerRequestInterface;
use Phpbench\Reports\Handler\BenchmarksHandler;
use Phpbench\Reports\Handler\BenchmarkHandler;

class RouterMiddleware
{
    const REQUEST_HANDLER = '_handler';
    const REQUEST_TOKENS = '_tokens';


    /**
     * @var Map
     */
    private $routeMap;

    /**
     * @var Matcher
     */
    private $matcher;


    public function __construct(Map $routeMap, Matcher $matcher)
    {
        $this->routeMap = $routeMap;
        $this->configureRoutes($routeMap);
        $this->matcher = $matcher;
    }

    public function __invoke(ServerRequestInterface $request, Response $response, callable $next)
    {
        $match = $this->matcher->match($request);

        if (!$match) {
            throw new \InvalidArgumentException(sprintf(
                'Could not match route for request: [%s] %s',
                $request->getMethod(),
                (string) $request->getUri()
            ));
        }

        $request = $request->withAttribute(self::REQUEST_HANDLER, $match->handler);
        foreach ($match->tokens as $tokenName => $tokenValue) {
            $request = $request->withAttribute($tokenName, $tokenValue);
        }

        return $next($request, $response);
    }


    private function configureRoutes(Map $routeMap)
    {
        $routeMap->get('benchmark', '/benchmark/{class}', BenchmarkHandler::class)
            ->tokens([
                'class' => '[A-Za-z0-9]+'
            ]);
        $routeMap->get('benchmarks', '/', BenchmarksHandler::class);
    }
}
