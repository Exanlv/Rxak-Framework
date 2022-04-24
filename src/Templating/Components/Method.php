<?php

namespace Rxak\Framework\Templating\Components;

use Rxak\Framework\Templating\Component;

class Method extends Component
{
    public string $method;

    public function __construct(
        string $method
    ) {
        $this->method = strtoupper($method);
    }

    public static function getFile(): string
    {
        return 'rxak/components/csrf';
    }
}