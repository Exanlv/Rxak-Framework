<?php

namespace Rxak\Framework\Routing;

use Exception;
use Rxak\Framework\Exception\Handler;
use Rxak\Framework\Exception\SafeException;
use Rxak\Framework\Validation\ValidationException;
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
                    if ($route->requiresValidation()) {
                        /**
                         * @var \Rxak\Framework\Validation\ValidationException[] $errors
                         */
                        $validation = null;
    
                        try {
                            $validation = $route->validate($request);
                        } catch (ValidationException $e) {
                            $validation = [$e];
                        }
    
                        if ($validation !== true) {
                            dd($validation);
                        }
                    }
    
                    return $routeHandler;
                }
    
                $finalRouteHandler = $routeHandler;
            }
    
            $errorCode = $finalRouteHandler === null ? 404 : 405;

            throw new SafeException(
                $errorCode,
                [
                    405 => 'Method not allowed.',
                    404 => 'Page not found.',
                ][$errorCode]
            );
        } catch (Exception $e) {
            Handler::getInstance()->reportError($e, $request);
        }
        
    }

    public function registerRoutes(RouteInterface ...$routes)
    {
        $this->routes = array_merge($this->routes, $routes);
    }
}