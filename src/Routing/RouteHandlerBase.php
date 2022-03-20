<?php

namespace Rxak\Framework\Routing;

use Rxak\Framework\Http\Request;
use Rxak\Framework\Templating\Templates\ErrorPage;
use Symfony\Component\HttpFoundation\Response;

abstract class RouteHandlerBase
{
    abstract public function handleRoute(Request $request);

    protected function returnResponse(Request $request, mixed $response)
    {
        if ($response instanceof Response) {
            $response->prepare($request);

            $response->send();
        } else {
            try {
                echo (string) $response;
            } catch (\Exception) {
                echo (string) new ErrorPage(502);
            }
        }
    }
}
