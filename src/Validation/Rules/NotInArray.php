<?php

namespace Rxak\Framework\Validation\Rules;

/**
 * @property bool $bail
 * @method void performCheck(string $field, callable $validation, string $check)
 */
trait NotInArray
{
    public function notInArray(string $field, array $disallowedValues)
    {
        $this->performCheck($field, function ($value) use ($disallowedValues) {
            return !in_array($value, $disallowedValues);
        }, "not_in_array");
    }
}