<?php

namespace Rxak\Framework;

use Dotenv\Dotenv;
use Exception;
use Illuminate\Database\Capsule\Manager;
use Rxak\Framework\Config\Config;
use Rxak\Framework\Filesystem\Filesystem;
use Rxak\Framework\Http\Request;
use Rxak\Framework\Logging\Logger;
use Rxak\Framework\Session\MessageBag;
use Symfony\Component\HttpFoundation\Response;

class App
{
    public static function init(): void
    {
        Logger::init();

        /**
         * Initialize database/eloquent
         */
        $capsule = new Manager();
        $capsule->addConnection(Config::get('database'));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        /**
         * Load .env
         */
        (Dotenv::createImmutable(Filesystem::getInstance()->baseDir))->load();
    }

    public static function terminate(): void
    {
        MessageBag::getInstance()?->terminate();
    }

    public static function env(string $key, string $default = ''): string
    {
        if (!isset($_ENV[$key])) {
            Logger::error(new Exception('Unable to load environment variable `' . $key . '`'));

            return $default;
        }

        return $_ENV[$key];
    }

    public static function returnResponse(Request $request, Response $response)
    {
        $response->prepare($request);

        $response->send();
    }
}