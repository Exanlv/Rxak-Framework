<?php

namespace Rxak\Framework\Templating;

use Rxak\Framework\Session\MessageBag;

abstract class Page extends Component
{
    public function hasValidationError(string $field)
    {
        return MessageBag::getInstance()->hasValidationError($field);
    }
}