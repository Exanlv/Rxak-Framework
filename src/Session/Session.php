<?php

namespace Rxak\Framework\Session;

class Session
{
    public static function set(string $key, mixed $data)
    {
        $_SESSION[$key] = $data;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : $default;
    }

    public static function exists(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }
}