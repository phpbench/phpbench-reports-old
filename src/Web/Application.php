<?php

namespace Phpbench\Reports;

use Phpbench\Reports\Container\ApplicationContainerBuilder;
use Phpbench\Reports\Middleware\RouterMiddleware;
use Phpbench\Reports\Middleware\HandlerMiddleware;
use Phpbench\Reports\Middleware\ContainerResolver;
use Pimple\Psr11\Container as Psr11Container;
use Pimple\Container;
use mindplay\middleman\Dispatcher;

class Application
{
    public static function createDispatcher(): Dispatcher
    {
        $container = new Container();
        $builder = new ApplicationContainerBuilder();
        $builder->build($container);
        $container['container'] = new Psr11Container($container);

        return new Dispatcher([
            RouterMiddleware::class,
            HandlerMiddleware::class,
        ], new ContainerResolver($container['container']));
    }
}
