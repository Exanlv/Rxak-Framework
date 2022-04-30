<?php

namespace Rxak\Framework\Routing;

use Exception;
use Rxak\Framework\App;
use Rxak\Framework\Config\Config;
use Rxak\Framework\Exception\Handler;
use Rxak\Framework\Http\Request;

abstract class Router
{
    private static ?Router $router = null;

    /**
     * @var \Rxak\Framework\Routing\RouteInterface[]
     */
    public array $routes = [];

    private function __construct()
    {
    }

    public static function getInstance(): Router
    {
        return self::$router ?? self::$router = new static();
    }

    abstract public function loadRoutes(): void;

    public function handleRequest(Request $request): void
    {
        try {
            $routeHandler = $this->getRoute($request);
            $response = $routeHandler->handleRoute($request);
        } catch (\Exception $e) {
            $response = Handler::getInstance()->handleError($e, $request);
        }

        App::terminate();
        
        App::returnResponse($request, $response);
    }

    public function getRoute(Request $request): RouteHandlerBase
    {
        try {
            $finalRouteHandler = null;
            foreach ($this->routes as $route) {
                $routeHandler = $route->uriOk($request);
                if ($routeHandler === false) {
                    continue;
                }

                if ($route->methodOk($request)) {
                    return $routeHandler;
                }
    
                $finalRouteHandler = $routeHandler;
            }
        } catch (Exception $e) {
            throw Config::get('exceptions.502');
        }

        throw Config::get($finalRouteHandler === null ? 'exceptions.404' : 'exceptions.405');
    }

    public function registerRoutes(RouteInterface ...$routes)
    {
        $this->routes = array_merge($this->routes, $routes);
    }
}