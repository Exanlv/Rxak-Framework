<?php

namespace Rxak\Framework\Validation\Rules;

/**
 * @property bool $bail
 * @method void performCheck(string $field, callable $validation, string $check)
 */
trait Max
{
    public function max(string $field, int $max)
    {
        $this->performCheck($field, function ($value) use ($max) {
            return $value < $max;
        }, "max:$max");
    }
}