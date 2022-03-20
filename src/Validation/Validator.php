<?php

namespace Rxak\Framework\Validation;

use Rxak\Framework\Validation\Rules\Length;
use Rxak\Framework\Http\Request;

abstract class Validator implements ValidatorInterface
{
    use Length;

    public bool $bail = false;

    /**
     * @var ValidationException[]
     */
    public array $errors = [];


    /**
     * @var bool $bail Whether or not to bail after a single exception is found
     */
    public function __construct(
        public Request $request
    ) {
        
    }

    public function validateResult(): bool|array
    {
        return count($this->errors) === 0 ? true : $this->errors;
    }

    /**
     * @throws ValidationException
     */
    public function performCheck(string $field, callable $validation, string $check, mixed $defaultValue = ''): void
    {
        if (!$validation($this->request->get($field, $defaultValue))) {
            $exception = new ValidationException($field, $check);

            if ($this->bail) {
                throw $exception;
            }

            $this->errors[] = $exception;
        }
    }
}