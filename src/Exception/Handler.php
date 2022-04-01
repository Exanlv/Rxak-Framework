<?php

namespace Rxak\Framework\Exception;

use Exception;
use Rxak\Framework\App;
use Rxak\Framework\Http\Request;
use Rxak\Framework\Http\Response;
use Rxak\Framework\Logging\Logger;
use Rxak\Framework\Session\MessageBag;
use Rxak\Framework\Templating\Templates\ErrorPage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

class Handler
{
    private static ?Handler $handler = null;

    protected static function expectsJson(Request $request): bool
    {
        return in_array('application/json', $request->getAcceptableContentTypes());
    }

    private function __construct()
    {
        
    }

    public static function getInstance(): Handler
    {
        return self::$handler ?? self::$handler = new Handler();
    }

    public function reportError(Exception $e, Request $request)
    {
        if ($e instanceof ValidationFailedException) {
            return $this->validationFailed($e, $request);
        }

        if (!$e instanceof SafeException) {
            Logger::error($e);
            $e = new SafeException(502);
        }

        $this->{
            self::expectsJson($request) ? 'respondJson' : 'respondHtml'
        }($e, $request);
    }

    protected function validationFailed(ValidationFailedException $e, Request $request)
    {
        $errors = [];

        /**
         * @var \Rxak\Framework\Validation\ValidationException $e;
         */
        foreach ($e->failedValidations as $failedValidation) {
            if (!isset($errors[$failedValidation->field])) {
                $errors[$failedValidation->field] = [];
            }

            $errors[$failedValidation->field][] = $failedValidation->check;
        }

        if (self::expectsJson($request)) {
            $response = new JsonResponse($errors);
        } else {
            $messageBag = MessageBag::getInstance();
            $messageBag->set('rxak.validation_errors', $errors);

            $response = new RedirectResponse($messageBag->get('rxak.previous_url', '/'));
        }

        App::terminate();

        $response->prepare($request);
    
        $response->send();
    }

    protected function respondJson(SafeException $e, Request $request)
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

    protected function respondHtml(SafeException $e, Request $request)
    {
        $response = new Response(
            new ErrorPage($e->httpResponseCode, $e->getMessage()),
            $e->httpResponseCode
        );

        $response->prepare($request);

        $response->send();
    }
}