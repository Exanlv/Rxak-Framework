<?php

namespace Rxak\Framework\Routing;

use Rxak\Framework\Config\Config;
use Rxak\Framework\Http\Request;

class RouteHandler extends RouteHandlerBase
{
    public array $matches;

    public function __construct(
        array $matches,
        private Route $route
    ) {
        $this->matches = array_splice($matches, 1);
    }

    public function handleRoute(Request $request)
    {
        $this->handleMappers();
        $this->handleValidation($request);

        if ($this->route->hasMiddlewares()) {
            $this->runMiddlewares($request);
        }

        $controller = $this->route->controller::getInstance();

        $params = [$request];

        if (count($this->matches) > 0) {
            $params = array_merge($params, $this->matches);
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

    private function handleMappers(): void
    {
        if ($this->route->mappers && count($this->matches) > 0) {
            for ($i = 0; $i < count($this->route->mappers); $i++) {
                /**
                 * Set $this->matches[$i] to the resulting value of a callable or a class implementing getFromRoute.
                 * If result is null $noResult will be true
                 * @var bool $noResult
                 */
                $noResult = null === $this->matches[$i] = is_callable($this->route->mappers[$i])
                    ? $this->matches[$i] = $this->route->mappers[$i]($this->matches[$i])
                    : $this->matches[$i] = $this->route->mappers[$i]::getFromRoute($this->matches[$i])
                ;

                if ($noResult) {
                    throw Config::get('exceptions.404');
                }
            }
        }
    }

    private function handleValidation(Request $request)
    {
        if ($this->route->requiresValidation()) {
            $this->route->validate($request);
        }
    }
}