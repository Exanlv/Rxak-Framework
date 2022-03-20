<?php

namespace Rxak\Framework\Validation\Rules;

/**
 * @property bool $bail
 * @method void performCheck(string $field, callable $validation, string $check)
 */
trait Length
{
    public function minLength(string $field, int $length)
    {
        $this->performCheck($field, function ($value) use ($length) {
            return strlen($value) > $length;
        }, 'min_length');
    }
}