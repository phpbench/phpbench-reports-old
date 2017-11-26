<?php

namespace Phpbench\Reports\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Exception;
use Phpbench\Reports\Security\AccessDeniedException;

class SecurityMiddleware
{
    const HEADER_API_KEY = 'X-API-Key';

    /**
     * @var string
     */
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        if (preg_match('{/api/*}', $request->getUri())) {
            $this->secureApi($request);
        }

        return $response;
    }

    private function secureApi(ServerRequestInterface $request): void
    {
        if (false === $request->hasHeader(self::HEADER_API_KEY)) {
            throw new AccessDeniedException(sprintf(
                'No %s header', self::HEADER_API_KEY
            ));
        }

        $apiKey = $request->getHeader(self::HEADER_API_KEY)[0];

        if ($apiKey === $this->apiKey) {
            return;
        }

        throw new AccessDeniedException(
            'Invalid API key'
        );
    }
}
