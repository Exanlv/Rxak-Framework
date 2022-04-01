<?php

namespace Rxak\Framework\Routing;

use Exception;
use Rxak\Framework\App;
use Rxak\Framework\Config\Config;
use Rxak\Framework\Exception\Handler;
use Rxak\Framework\Http\Request;

class Router
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
        return self::$router ?? self::$router = new Router();
    }

    public function loadRoutes(): void
    {
        include __DIR__ . '/Routes/Routes.php';
    }

    public function handleRequest(Request $request): void
    {
        $routeHandler = $this->getRoute($request);

        try {
            $routeHandler->handleRoute($request);
        } catch (\Exception $e) {
            Handler::getInstance()->reportError($e, $request);
        }

        App::terminate();
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
    
            throw Config::get($finalRouteHandler === null ? 'exceptions.404' : 'exceptions.405');
        } catch (Exception $e) {
            Handler::getInstance()->reportError($e, $request);
        }
        
    }

    public function registerRoutes(RouteInterface ...$routes)
    {
        $this->routes = array_merge($this->routes, $routes);
    }
}