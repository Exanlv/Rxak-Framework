<?php

namespace Rxak\Framework\Http;

use stdClass;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

class Request extends HttpFoundationRequest
{
    private ?stdClass $jsonDecodedBody = null;

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->getContentType() === 'json') {
            if ($this->jsonDecodedBody === null) {
                $this->jsonDecodedBody = json_decode($this->getContent());
            }

            return property_exists($this->jsonDecodedBody, $key) ? $this->jsonDecodedBody->{$key} : $default;
        }

        return parent::get($key, $default);
    }

    public function getMethod(): string
    {
        $httpMethod = parent::getMethod();

        return $httpMethod === 'POST' ? $this->get('_method', $httpMethod) : $httpMethod;
    }
}
