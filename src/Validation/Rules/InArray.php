<?php

namespace Rxak\Framework\Validation\Rules;

/**
 * @property bool $bail
 * @method void performCheck(string $field, callable $validation, string $check)
 */
trait InArray
{
    public function inArray(string $field, array $allowedValues)
    {
        $this->performCheck($field, function ($value) use ($allowedValues) {
            return in_array($value, $allowedValues);
        }, "in_array");
    }
}