<?php

namespace Rxak\Framework\Middleware;

use Rxak\Framework\Http\Request;

class StartSessionMiddleware extends BaseMiddleware
{
    public function handle(callable $next, Request $request, array $middlewares): void
    {
        session_start();
        
        $next($request, $middlewares);
    }
}