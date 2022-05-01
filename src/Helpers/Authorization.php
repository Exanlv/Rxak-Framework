<?php

namespace Rxak\Framework\Helpers;

use Rxak\Framework\Models\Authenticatable;
use Rxak\Framework\Session\Session;

class Authorization
{
    private const SESSION_KEY = 'rxak.authentication';
    private static ?Authenticatable $authentication = null;

    public static function authorize(Authenticatable $authenticatable)
    {
        self::$authentication = $authenticatable;

        Session::set(self::SESSION_KEY, [
            'id' => $authenticatable->getId(),
            'class' => get_class($authenticatable)
        ]);
    }

    public static function get(): ?Authenticatable
    {
        if (self::$authentication === null) {
            if (!Session::exists(self::SESSION_KEY)) {
                return null;
            }

            $authentication = Session::get(self::SESSION_KEY);

            self::$authentication = $authentication['class']::getById($authentication['id']);
        }

        return self::$authentication;
    }

    public static function isAuthorized(): bool
    {
        return self::get() !== null;
    }
}