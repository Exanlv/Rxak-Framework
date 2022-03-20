<?php

namespace Rxak\Framework\Validation;

use Exception;

class ValidationException extends Exception
{
    public function __construct(
        public string $field,
        public string $check
    ) {
        
    }
}