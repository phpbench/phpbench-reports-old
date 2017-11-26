<?php

use Phpbench\Reports\Application;
use Psr\Http\Message\RequestInterface;
use Zend\Diactoros\Response;
use Phpbench\Reports\Env;

require(__DIR__ . '/../vendor/autoload.php');

putenv(Env::API_KEY . '=valid-api-key');

$dispatcher = Application::createDispatcher();

return function (RequestInterface $request) use ($dispatcher) {
    return $dispatcher->dispatch($request, new Response());
};
