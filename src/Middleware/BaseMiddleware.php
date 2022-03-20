<?php

namespace Rxak\Framework\Middleware;

use Rxak\Framework\Http\Request;

abstract class BaseMiddleware
{
    /**
     * @var string[] $middlewares
     */
    abstract public function handle(callable $next, Request $request, array $middlewares): void;
}