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
use Symfony\Component\Yaml\Yaml;
use Phpbench\Reports\Handler\ApiSuitePostHandler;
use Phpbench\Reports\Handler\ApiIterationsPostHandler;
use Phpbench\Reports\Elastic\ElasticStorage;
use Phpbench\Reports\Middleware\JsonErrorMiddleware;
use Phpbench\Reports\Middleware\SecurityMiddleware;
use Symfony\Component\Dotenv\Dotenv;
use Phpbench\Reports\Env;

class ApplicationContainerBuilder
{
    private $config = [];

    public function build(Container $container)
    {
        $this->initConfig();

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

        $container[JsonErrorMiddleware::class] = function (Container $container) {
            return new JsonErrorMiddleware();
        };

        $container[SecurityMiddleware::class] = function (Container $container) {
            return new SecurityMiddleware($this->config['api_key']);
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

        $container[ApiSuitePostHandler::class] = function (Container $container) {
            return new ApiSuitePostHandler($container['elastic.storage']);
        };

        $container[ApiIterationsPostHandler::class] = function (Container $container) {
            return new ApiIterationsPostHandler($container['elastic.storage']);
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
            return ClientBuilder::fromConfig($this->config['elastic_search']);
        };

        $container['elastic.storage'] = function (Container $container) {
            return new ElasticStorage($container['elastic.client']);
        };
    }

    public function initConfig()
    {
        $defaultConfig = [
            'elastic_search' => [],
            'api_key' => null,
        ];

        $config = [];
        if (file_exists($this->configDir())) {
            $config = (array) Yaml::parse(file_get_contents($this->configDir()));
        }

        $config = array_merge($defaultConfig, $config);

        if ($apiKey = getenv(Env::API_KEY)) {
            $config['api_key'] = $apiKey;
        }

        $this->config = $config;
    }

    private function configDir(): string
    {
        return __DIR__ . '/../../../config/parameters.yml';
    }
}
