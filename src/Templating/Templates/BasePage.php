<?php

namespace Rxak\Framework\Templating\Templates;

use Rxak\Framework\Templating\Page;

class BasePage extends Page
{
    public function __construct(
        public string $body = '',
        public string $title = 'Rxak'
    ) {
        
    }

    public static function getFile(): string
    {
        return 'views/base';
    }
}