<?php

namespace Rxak\Framework\Validation\Rules;

/**
 * @property bool $bail
 * @method void performCheck(string $field, callable $validation, string $check)
 */
trait Min
{
    public function min(string $field, int $min)
    {
        $this->performCheck($field, function ($value) use ($min) {
            return strlen($value) < $min;
        }, "min:$min");
    }
}