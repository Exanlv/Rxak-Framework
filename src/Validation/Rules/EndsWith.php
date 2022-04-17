<?php

namespace Rxak\Framework\Validation\Rules;

/**
 * @property bool $bail
 * @method void performCheck(string $field, callable $validation, string $check)
 */
trait EndsWith
{
    public function endsWith(string $field, string $end)
    {
        $this->performCheck($field, function ($value) use ($end) {
            return str_ends_with($value, $end);
        }, "ends_with:$end");
    }
}