<?php

use Rxak\Framework\Config\Config;

function pub(string $url) {
    return Config::get('app')::env('PUBLIC_PREFIX', '/') . $url;
}
