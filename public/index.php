<?php

use Rxak\Framework\App;
use Rxak\Framework\Filesystem\Filesystem;
use Rxak\Framework\Routing\Router;
use Rxak\Framework\Http\Request;

require '../vendor/autoload.php';

Filesystem::init(__DIR__ . '/..');

$request = Request::createFromGlobals();

App::init();

$router = Router::getInstance();

$router->loadRoutes();
$router->handleRequest($request);
