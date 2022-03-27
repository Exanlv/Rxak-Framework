<?php

namespace Rxak\Framework\Validation\Validators;

use Rxak\Framework\Validation\Validator;

class FeedbackValidator extends Validator
{
    public function authorized(): bool
    {
        return false;
    }

    public function validate(): void
    {
        $this->minLength('email', 16);
        $this->minLength('name', 3);
    }
}