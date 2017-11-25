<?php

use Phpbench\Reports\Application;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;

require(__DIR__ . '/../vendor/autoload.php');

$dispatcher = Application::createDispatcher();

return function (RequestInterface $request) use ($dispatcher) {
    return $dispatcher->dispatch($request, new Response());
};
