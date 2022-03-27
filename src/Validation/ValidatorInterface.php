<?php

namespace Rxak\Framework\Validation;

use Rxak\Framework\Http\Request;

interface ValidatorInterface
{
    /**
     * @throws ValidationException
     */
    public function validate();

    public function authorized(): bool;

    public function validateResult(): bool|array;
}