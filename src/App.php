<?php

namespace Rxak\Framework;

use Illuminate\Database\Capsule\Manager;
use Rxak\Framework\Config\Config;

class App
{
    public static function init(): void
    {
        $capsule = new Manager();

        $capsule->addConnection(Config::get('database'));

        $capsule->setAsGlobal();

        $capsule->bootEloquent();
    }
}