<?php

namespace Rxak\Framework\Middleware;

use Rxak\Framework\Http\Request;
use Rxak\Framework\Session\MessageBag;

class StartSessionMiddleware extends BaseMiddleware
{
    public function handle(callable $next, Request $request, array $middlewares): void
    {
        session_start();

        MessageBag::init();

        $next($request, $middlewares);
    }
}