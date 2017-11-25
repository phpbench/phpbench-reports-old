<?php

namespace Phpbench\Reports;

use Slim\App;
use Psr\Http\Message\RequestInterface;
use mindplay\middleman\Dispatcher;
use Phpbench\Reports\Middleware\RouterMiddleware;
use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use Phpbench\Reports\Middleware\HandlerMiddleware;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Aura\Router\Matcher;
use Aura\Router\Map;
use Pimple\Container;
use Pimple\Psr11\Container as Psr11Container;
use Phpbench\Reports\Middleware\ContainerResolver;
use Aura\Router\Route;
use Aura\Router\Rule\RuleIterator;
use Phpbench\Reports\Handler\BenchmarkHandler;
use Phpbench\Reports\Repository\VariantRepository;
use Elastica\Index;

class Application
{
    public static function createDispatcher(): Dispatcher
    {
        $container = new Container();
        self::configureContainer($container);
        $container['container'] = new Psr11Container($container);

        return new Dispatcher([
            RouterMiddleware::class,
            HandlerMiddleware::class,
        ], new ContainerResolver($container['container']));
    }

    private static function configureContainer(Container $container)
    {
        $container['twig'] = function () {
            return new Environment(
                new FilesystemLoader([
                    __DIR__ . '/Templates'
                ])
            );
        };

        $container[LoggerInterface::class] = function () {
            return new NullLogger();
        };

        $container[RouterMiddleware::class] = function (Container $container) {
            $map = new Map(new Route());
            return new RouterMiddleware(
                $map,
                new Matcher($map, $container[LoggerInterface::class], new RuleIterator())
            );
        };

        $container[HandlerMiddleware::class] = function (Container $container) {
            return new HandlerMiddleware($container['container']);
        };

        $container[BenchmarkHandler::class] = function (Container $container) {
            return new BenchmarkHandler($container['twig'], $container[VariantRepository::class]);
        };

        $container[VariantRepository::class] = function (Container $container) {
            return new VariantRepository($container['elastic.client']);
        };

        $container['elastic.client'] = function (Container $container) {
            return ClientBuilder::create()
                ->setHosts([
                    'localhost:9201',
                ])->build();
        };
    }
}
