<?php

namespace Rxak\Framework\Exception;

use Exception;

class SafeException extends Exception
{
    public function __construct(public int $httpResponseCode, ?string $message = 'Server exception.')
    {
        parent::__construct($message);
    }
}