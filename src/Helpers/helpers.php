<?php

use Rxak\Framework\App;

function pub(string $url) {
    return App::env('PUBLIC_PREFIX', '/') . $url;
}
