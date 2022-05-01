<?php

namespace Rxak\Framework\Validation\Rules;

/**
 * @property bool $bail
 * @method void performCheck(string $field, callable $validation, string $check)
 */
trait MaxLength
{
    public function maxLength(string $field, int $length)
    {
        $this->performCheck($field, function ($value) use ($length) {
            return strlen($value) <= $length;
        }, "max_length:$length");
    }
}