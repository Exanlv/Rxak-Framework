<?php

namespace Rxak\Framework\Templating\Templates;

use Rxak\Framework\Templating\Page;
use Rxak\Framework\Templating\Templates\BasePage;

class UserRegisterPage extends Page
{
    public function __construct(
        public string $body
    ) {
        
    }

    public static function getFile(): string
    {
        return 'views/user/register';
    }

    public function build(): string
    {
        return (string) new BasePage($this->buildPart());
    }
}