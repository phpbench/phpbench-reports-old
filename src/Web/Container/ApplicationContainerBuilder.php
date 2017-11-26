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
use Phpbench\Reports\Repository\BenchmarkRepository;
use Elasticsearch\ClientBuilder;
use Pimple\Container;
use Psr\Log\NullLogger;
use Phpbench\Reports\Handler\BenchmarksHandler;
use Aura\Router\RouterContainer;
use Phpbench\Reports\Twig\ReportExtension;
use Phpbench\Reports\Handler\BenchmarkHandler;
use Phpbench\Reports\Repository\SubjectRepository;
use Phpbench\Reports\Handler\SubjectHandler;
use Phpbench\Reports\Handler\VariantHandler;
use Phpbench\Reports\Repository\IterationRepository;

class ApplicationContainerBuilder
{
    public function build(Container $container)
    {
        $container['twig'] = function (Container $container) {
            $environment = new Environment(
                new FilesystemLoader([
                    __DIR__ . '/../Templates'
                ]),
                [
                    'strict_variables' => true,
                ]
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
            return new RouterMiddleware(
                $container[RouterContainer::class]->getMap(),
                $container[RouterContainer::class]->getMatcher()
            );
        };

        $container['route.generator'] = function (Container $container) {
            return $container[RouterContainer::class]->getGenerator();
        };

        $container[HandlerMiddleware::class] = function (Container $container) {
            return new HandlerMiddleware($container['container']);
        };

        $container[BenchmarksHandler::class] = function (Container $container) {
            return new BenchmarksHandler($container['twig'], $container[BenchmarkRepository::class]);
        };

        $container[VariantHandler::class] = function (Container $container) {
            return new VariantHandler($container['twig'], $container[IterationRepository::class]);
        };

        $container[SubjectHandler::class] = function (Container $container) {
            return new SubjectHandler($container['twig'], $container[SubjectRepository::class]);
        };

        $container[BenchmarkHandler::class] = function (Container $container) {
            return new BenchmarkHandler($container['twig'], $container[SubjectRepository::class]);
        };

        $container[BenchmarkRepository::class] = function (Container $container) {
            return new BenchmarkRepository($container['elastic.client']);
        };

        $container[SubjectRepository::class] = function (Container $container) {
            return new SubjectRepository($container['elastic.client']);
        };

        $container[IterationRepository::class] = function (Container $container) {
            return new IterationRepository($container['elastic.client']);
        };

        $container['elastic.client'] = function (Container $container) {
            return ClientBuilder::create()
                ->setHosts([
                    'localhost:9200',
                ])->build();
        };
    }
}
