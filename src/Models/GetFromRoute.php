<?php

namespace Rxak\Framework\Models;

trait GetFromRoute
{
    public static function getFromRoute(string $id)
    {
        return self::find($id);
    }
}