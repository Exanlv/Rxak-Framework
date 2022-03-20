<?php

namespace Rxak\Framework\Templating\Templates;

use Rxak\Framework\Templating\Page;

class ErrorPage extends Page
{
    public function __construct(
        public int $code = 500,
        public string $message = ''
    ) {
        
    }

    public static function getFile(): string
    {
        return 'views/error';
    }
}