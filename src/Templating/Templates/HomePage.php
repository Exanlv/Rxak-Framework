<?php

namespace Rxak\Framework\Templating\Templates;

use Rxak\Framework\Templating\Page;
use Rxak\Framework\Templating\Templates\BasePage;

class HomePage extends Page
{
    public function __construct(
        public string $body
    ) {
        
    }

    public static function getFile(): string
    {
        return 'views/home';
    }

    public function build(): string
    {
        return (string) new BasePage($this->buildPart());
    }
}