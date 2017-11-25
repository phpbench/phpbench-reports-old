<?php

namespace Phpbench\Reports;

use Slim\App;
use Psr\Http\Message\RequestInterface;
use mindplay\middleman\Dispatcher;
use DI\ContainerBuilder;
use mindplay\middleman\InteropResolver;
use Phpbench\Reports\Middleware\RouterMiddleware;
use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use Phpbench\Reports\Middleware\HandlerMiddleware;
use Psr\Container\ContainerInterface;
use Twig\Environment;
use DI\Container;
use Twig\Loader\FilesystemLoader;

class Application
{
    public static function createDispatcher(): Dispatcher
    {
        $container = ContainerBuilder::buildDevContainer();
        self::configureContainer($container);

        return new Dispatcher([
            RouterMiddleware::class,
            HandlerMiddleware::class,
        ], new InteropResolver($container));
    }

    private static function configureContainer(Container $container)
    {
        $container->set(LoggerInterface::class, new NullLogger());
        $container->set(\Twig_Environment::class, new Environment(
            new FilesystemLoader([
                __DIR__ . '/Templates'
            ])
        ));
    }
}
