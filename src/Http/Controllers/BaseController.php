<?php

namespace Rxak\Framework\Http\Controllers;

abstract class BaseController
{
    private static ?self $controller = null;

    protected function __construct()
    {
        
    }

    public static function getInstance(): self
    {
        return self::$controller ?? self::$controller = new static();
    }
}