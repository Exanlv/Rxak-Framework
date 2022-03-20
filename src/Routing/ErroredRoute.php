<?php

namespace Rxak\Framework\Routing;

use Rxak\Framework\Http\Request;
use Rxak\Framework\Http\Response;
use Rxak\Framework\Templating\Templates\ErrorPage;

class ErroredRoute extends RouteHandlerBase
{
    public function __construct(
        public int $code,
        public string $message = ''
    ) {
        
    }

    public function handleRoute(Request $request)
    {
        $response = new Response(
            new ErrorPage(
                $this->code,
                $this->message
            ),
            $this->code
        );

        $this->returnResponse($request, $response);
    }
}