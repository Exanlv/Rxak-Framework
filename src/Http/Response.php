<?php

namespace Rxak\Framework\Http;

use Rxak\Framework\Templating\Page;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class Response extends HttpFoundationResponse
{
    public function __construct(Page $page, int $statusCode)
    {
        parent::__construct(
            (string) $page,
            $statusCode,
            ['content-type' => 'text/html'],
        );
    }
}