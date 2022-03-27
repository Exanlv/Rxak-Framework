<?php

namespace Rxak\Framework\Middleware;

use Rxak\Framework\Config\Config;
use Rxak\Framework\Http\Request;
use Rxak\Framework\Session\Session;

class CsrfMiddleware extends BaseMiddleware
{
    public static $sessionKey = 'CSRF_TOKEN';

    public function handle(callable $next, Request $request, array $middlewares): void
    {
        if ($request->isMethod('POST')) {
            if (!$this->verifyCsrf($request)) {
                throw Config::get('exceptions.419');
            }
        }

        Session::set(self::$sessionKey, $this->generateCsrfToken());
        
        $next($request, $middlewares);
    }

    private static function verifyCsrf(Request $request): bool
    {
        return Session::exists(self::$sessionKey)
            && $request->get(self::$sessionKey) === Session::get(self::$sessionKey);
        ;
    }

    private function generateCsrfToken()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);

        $randomSections = [];

        for ($i = 0; $i < 8; $i++) {
            $string = '';
            for ($j = 0; $j < 8; $j++) {
                $string .= $characters[rand(0, $charactersLength - 1)];
            }

            $randomSections[] = $string;
        }

        return implode('-', $randomSections);
    }

    public static function getCsrfToken(): string
    {
        return Session::get(self::$sessionKey, '');
    }
}