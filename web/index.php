<?php

use Phpbench\Reports\Application;
use Zend\Diactoros\ServerRequestFactory;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\Response;

require(__DIR__ . '/../vendor/autoload.php');

$dispatcher = Application::createDispatcher();

$request = ServerRequestFactory::fromGlobals();
$response = $dispatcher->dispatch($request, new Response());
$emitter = new SapiEmitter();
$emitter->emit($response);
