<?php

namespace Phpbench\Reports\Container;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Psr\Log\LoggerInterface;
use Phpbench\Reports\Middleware\RouterMiddleware;
use Aura\Router\Map;
use Aura\Router\Route;
use Aura\Router\Matcher;
use Aura\Router\Rule\RuleIterator;
use Phpbench\Reports\Middleware\HandlerMiddleware;
use Phpbench\Reports\Repository\VariantRepository;
use Elasticsearch\ClientBuilder;
use Pimple\Container;
use Psr\Log\NullLogger;
use Phpbench\Reports\Handler\BenchmarkHandler;
use Aura\Router\RouterContainer;
use Phpbench\Reports\Twig\ReportExtension;

class ApplicationContainerBuilder
{
    public function build(Container $container)
    {
        $container['twig'] = function (Container $container) {
            $environment = new Environment(
                new FilesystemLoader([
                    __DIR__ . '/../Templates'
                ])
            );
            $environment->addExtension(new ReportExtension(
                $container['route.generator']
            ));

            return $environment;
        };

        $container[LoggerInterface::class] = function () {
            return new NullLogger();
        };

        $container[RouterContainer::class] = function () {
            return new RouterContainer();
        };

        $container[RouterMiddleware::class] = function (Container $container) {
            $map = $container[RouterContainer::class]->getMap();
            return new RouterMiddleware(
                $map,
                new Matcher($map, $container[LoggerInterface::class], new RuleIterator())
            );
        };

        $container['route.generator'] = function (Container $container) {
            return $container[RouterContainer::class]->getGenerator();
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
