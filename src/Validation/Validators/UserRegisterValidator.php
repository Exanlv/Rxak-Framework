<?php

namespace Rxak\Framework\Validation\Validators;

use Rxak\Framework\Validation\Validator;

class UserRegisterValidator extends Validator
{
    public function authorized(): bool
    {
        return true;
    }

    public function validate(): void
    {
        $this->minLength('email', 5);
        $this->minLength('username', 3);
    }
}