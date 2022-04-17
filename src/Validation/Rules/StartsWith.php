<?php

namespace Rxak\Framework\Validation\Rules;

/**
 * @property bool $bail
 * @method void performCheck(string $field, callable $validation, string $check)
 */
trait StartsWith
{
    public function startsWith(string $field, string $start)
    {
        $this->performCheck($field, function ($value) use ($start) {
            return str_starts_with($value, $start);
        }, "starts_with:$start");
    }
}