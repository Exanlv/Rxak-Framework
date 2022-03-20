<?php

namespace Rxak\Framework\Exception;

use Exception;
use Rxak\Framework\Http\Request;
use Rxak\Framework\Http\Response;
use Rxak\Framework\Templating\Templates\ErrorPage;
use Symfony\Component\HttpFoundation\JsonResponse;

class Handler
{
    private static ?Handler $handler = null;

    private function __construct()
    {
        
    }

    public static function getInstance(): Handler
    {
        return self::$handler ?? self::$handler = new Handler();
    }

    public function reportError(Exception $e, Request $request)
    {
        if (!$e instanceof SafeException) {
            $e = new SafeException(502);
        }

        $this->{
            in_array('application/json', $request->getAcceptableContentTypes()) ? 'respondJson' : 'respondHtml'
        }($e, $request);
    }

    public function respondJson(SafeException $e, Request $request)
    {
        $response = new JsonResponse(
            [
                'code' => $e->httpResponseCode,
                'message' => $e->getMessage() ?? null,
            ],
            $e->httpResponseCode
        );

        $response->prepare($request);

        $response->send();
    }

    public function respondHtml(SafeException $e, Request $request)
    {
        $response = new Response(
            new ErrorPage($e->httpResponseCode, $e->getMessage()),
            $e->httpResponseCode
        );

        $response->prepare($request);

        $response->send();
    }
}