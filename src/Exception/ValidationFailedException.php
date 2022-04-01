<?php

namespace Rxak\Framework\Exception;

use Exception;

class ValidationFailedException extends Exception
{
    /**
     * @var \Rxak\Framework\Validation\ValidationException[] $failedValidations
     */
    public function __construct(public array $failedValidations)
    {
        parent::__construct('Validation failed.');
    }
}