<?php

namespace Phpbench\Reports\Middleware;

use Psr\Container\ContainerInterface;
use RuntimeException;

class ContainerResolver
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke($name)
    {
        if (! is_string($name)) {
            return $name; // nothing to resolve (may be a closure or other callable middleware object)
        }

        if ($this->container->has($name)) {
            return $this->container->get($name);
        }

        throw new RuntimeException("unable to resolve middleware component name: {$name}");
    }
}
