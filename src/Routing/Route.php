<?php

namespace Rxak\Framework\Routing;

use Rxak\Framework\Http\Request;

class Route implements RouteInterface
{
    /**
     * @var string[] $middlewares
     */
    public function __construct(
        public string $pattern,
        public string $httpMethod,
        public string $controller,
        public string $method,
        public ?string $validator = null,
        public ?array $middlewares = null
    ) {
        
    }

    public function uriOk(Request $request): false|RouteHandler
    {
        $matches = [];
        if (preg_match($this->pattern, $request->getPathInfo(), $matches) === 0) {
            return false;
        }

        return new RouteHandler($matches, $this);
    }

    public function methodOk(Request $request): bool
    {
        return $this->httpMethod === $request->getMethod();
    }

    public function requiresValidation(): bool
    {
        return $this->validator !== null;
    }

    public function validate(Request $request): bool|array
    {
        /**
         * @var \Rxak\Framework\Validation\ValidatorInterface
         */
        $validator = new $this->validator($request);

        $validator->validate();

        return $validator->validateResult();
    }

    public static function get(
        string $pattern,
        string $controller,
        string $method,
        ?string $validator = null,
        ?array $middlewares = null
    ) {
        return new self(
            $pattern,
            'GET',
            $controller,
            $method,
            $validator,
            $middlewares
        );
    }

    public static function post(
        string $pattern,
        string $controller,
        string $method,
        ?string $validator = null,
        ?array $middlewares = null
    ) {
        return new self(
            $pattern,
            'POST',
            $controller,
            $method,
            $validator,
            $middlewares
        );
    }

    public static function put(
        string $pattern,
        string $controller,
        string $method,
        ?string $validator = null,
        ?array $middlewares = null
    ) {
        return new self(
            $pattern,
            'PUT',
            $controller,
            $method,
            $validator,
            $middlewares
        );
    }

    public static function patch(
        string $pattern,
        string $controller,
        string $method,
        ?string $validator = null,
        ?array $middlewares = null
    ) {
        return new self(
            $pattern,
            'PATCH',
            $controller,
            $method,
            $validator,
            $middlewares
        );
    }

    public static function delete(
        string $pattern,
        string $controller,
        string $method,
        ?string $validator = null,
        ?array $middlewares = null
    ) {
        return new self(
            $pattern,
            'DELETE',
            $controller,
            $method,
            $validator,
            $middlewares
        );
    }

    public static function options(
        string $pattern,
        string $controller,
        string $method,
        ?string $validator = null,
        ?array $middlewares = null
    ) {
        return new self(
            $pattern,
            'OPTIONS',
            $controller,
            $method,
            $validator,
            $middlewares
        );
    }

    public static function head(
        string $pattern,
        string $controller,
        string $method,
        ?string $validator = null,
        ?array $middlewares = null
    ) {
        return new self(
            $pattern,
            'HEAD',
            $controller,
            $method,
            $validator,
            $middlewares
        );
    }

    /**
     * @var Route[] $routes
     * @return Route[]
     */
    public static function group(
        array $middlewares = null,
        array $routes = []
    ): array {
        foreach ($routes as &$route) {
            $route->middlewares = $middlewares;
        }

        return $routes;
    }

    public function hasMiddlewares(): bool
    {
        return $this->middlewares === null ? false : count($this->middlewares) > 0;
    }
}