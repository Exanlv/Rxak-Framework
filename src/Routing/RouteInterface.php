<?php

namespace Rxak\Framework\Routing;

use Rxak\Framework\Http\Request;

interface RouteInterface
{
    public function uriOk(Request $request): false|RouteHandler;

    public function methodOk(Request $request): bool;

    public function requiresValidation(): bool;

    /**
     * @return bool|\Rxak\Framework\Validation\ValidationException[]
     */
    public function validate(Request $request): bool|array;
}