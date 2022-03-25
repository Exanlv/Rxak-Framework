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

    public static function create(string $pattern, string $controller, string $method, string $httpMethod, array $options): Route
    {
        $options = array_merge([
                'validator' => null,
                'middlewares' => null,
            ], $options
        );

        return new Route(
            $pattern,
            $httpMethod,
            $controller,
            $method,
            $options['validator'],
            $options['middlewares']
        );
    }

    public static function get(string $pattern, string $controller, string $method, array $options = [])
    {
        return self::create($pattern, $controller, $method, 'GET', $options);
    }

    public static function post(string $pattern, string $controller, string $method, array $options = [])
    {
        return self::create($pattern, $controller, $method, 'POST', $options);
    }

    public static function put(string $pattern, string $controller, string $method, array $options = [])
    {
        return self::create($pattern, $controller, $method, 'PUT', $options);
    }

    public static function patch(string $pattern, string $controller, string $method, array $options = [])
    {
        return self::create($pattern, $controller, $method, 'PATCH', $options);
    }

    public static function delete(string $pattern, string $controller, string $method, array $options = [])
    {
        return self::create($pattern, $controller, $method, 'DELETE', $options);
    }

    public static function options(string $pattern, string $controller, string $method, array $options = [])
    {
        return self::create($pattern, $controller, $method, 'OPTIONS', $options);
    }

    public static function head(string $pattern, string $controller, string $method, array $options = [])
    {
        return self::create($pattern, $controller, $method, 'HEAD', $options);
    }

    /**
     * @var Route[] $routes
     * @return Route[]
     */
    public static function group(
        array $options = null,
        array $routes = []
    ): array {
        foreach ($routes as &$route) {
            foreach ($options as $key => $value) {
                $route->{$key} = $value;
            }
        }

        return $routes;
    }

    public function hasMiddlewares(): bool
    {
        return $this->middlewares === null ? false : count($this->middlewares) > 0;
    }
}