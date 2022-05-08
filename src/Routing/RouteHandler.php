<?php

namespace Rxak\Framework\Routing;

use Error;
use ReflectionMethod;
use Rxak\Framework\Config\Config;
use Rxak\Framework\Exception\ValidationFailedException;
use Rxak\Framework\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RouteHandler extends RouteHandlerBase
{
    public array $matches;

    public function __construct(
        array $matches,
        private Route $route
    ) {
        $this->matches = array_splice($matches, 1);
    }

    public function handleRoute(Request $request): Response
    {
        $this->handleMappers();

        if ($this->route->hasMiddlewares()) {
            $this->runMiddlewares($request);
        }

        $this->handleValidation($request);

        $controller = $this->route->controller::getInstance();

        $params = [$request];

        if (count($this->matches) > 0) {
            $params = array_merge($params, $this->matches);
        }

        try {
            $response = $controller->{$this->route->method}(...$params);
        } catch (Error $e) {
            if ($e->getMessage() === ('Call to undefined method ' . $this->route->controller . '::' . $this->route->method . '()')) {
                throw Config::get('exceptions.501');
            }

            throw $e;
        }

        return $response;
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
        $reflection = new ReflectionMethod(
            $this->route->controller,
            $this->route->method
        );

        $i = 0;
        foreach ($reflection->getParameters() as $parameter) {
            if ($parameter->getType()->getName() === Request::class) {
                continue;
            }

            $noResult = isset($this->matches[$i])
                ? null === $this->matches[$i] = $parameter->getType()->getName()::getFromRoute($this->matches[$i])
                : true
            ;

            if ($noResult && !$parameter->allowsNull()) {
                throw Config::get('exceptions.404');
            }

            $i++;
        }
    }

    private function handleValidation(Request $request): void
    {
        if (!$this->route->requiresValidation()) {
            return;
        }

        $result = $this->route->validate($request);

        if ($result === true) {
            return;
        }

        throw new ValidationFailedException($result);
    }
}