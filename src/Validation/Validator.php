<?php

namespace Rxak\Framework\Validation;

use Rxak\Framework\Http\Request;
use Rxak\Framework\Validation\Rules\EndsWith;
use Rxak\Framework\Validation\Rules\InArray;
use Rxak\Framework\Validation\Rules\Max;
use Rxak\Framework\Validation\Rules\MaxLength;
use Rxak\Framework\Validation\Rules\Min;
use Rxak\Framework\Validation\Rules\MinLength;
use Rxak\Framework\Validation\Rules\NotInArray;
use Rxak\Framework\Validation\Rules\StartsWith;

abstract class Validator implements ValidatorInterface
{
    use EndsWith;
    use InArray;
    use Max;
    use MaxLength;
    use Min;
    use MinLength;
    use NotInArray;
    use StartsWith;

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

            $this->errors[] = $exception;
        }
    }
}