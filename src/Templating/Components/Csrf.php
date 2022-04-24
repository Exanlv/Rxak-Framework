<?php

namespace Rxak\Framework\Templating\Components;

use Rxak\Framework\Middleware\CsrfMiddleware;
use Rxak\Framework\Templating\Component;

class Csrf extends Component
{
    public string $formName;
    public string $csrfToken;

    public function __construct()
    {
        $this->formName = CsrfMiddleware::$sessionKey;
        $this->csrfToken = CsrfMiddleware::getCsrfToken();
    }

    public static function getFile(): string
    {
        return 'rxak/components/csrf';
    }
}