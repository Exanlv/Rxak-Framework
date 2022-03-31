<?php

use Rxak\Framework\App;
use Rxak\Framework\Routing\Router;
use Rxak\Framework\Http\Request;

require '../vendor/autoload.php';

App::init();

$request = Request::createFromGlobals();

$router = Router::getInstance();

$router->loadRoutes();
$router->handleRequest($request);
