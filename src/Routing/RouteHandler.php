<?php

namespace Rxak\Framework\Routing;

use Rxak\Framework\Http\Request;
use Rxak\Framework\Http\Response;
use Rxak\Framework\Templating\Templates\ErrorPage;

class RouteHandler extends RouteHandlerBase
{
    public function __construct(
        public array $matches,
        private Route $route
    ) {
        
    }

    public function handleRoute(Request $request)
    {
        if ($this->route->hasMiddlewares()) {
            $this->runMiddlewares($request);
        }

        $controller = $this->route->controller::getInstance();

        $params = [$request];

        if (count($this->matches) > 0) {
            $params = array_merge($params, array_splice($this->matches, 1));
        }

        $response = $controller->{$this->route->method}(...$params);

        $this->returnResponse($request, $response);
    }

    public function runMiddlewares(Request $request)
    {
        $middlewares = array_values($this->route->middlewares);

        $runMiddlewares = function (Request $request, array $middlewares) use (&$runMiddlewares) {
            if (count($middlewares) === 0) {
                return;
            }

            $middlewareClass = array_splice($middlewares, 0, 1)[0];

            /**
             * @var \Rxak\Framework\Middleware\BaseMiddleware
             */
            $middleware = new $middlewareClass();

            $middleware->handle($runMiddlewares, $request, $middlewares);
        };

        $runMiddlewares($request, $middlewares);
    }
}