<?php

namespace Rxak\Framework\Routing\Routes;

use Rxak\Framework\Http\Controllers\Home\HomeController;
use Rxak\Framework\Middleware\AnotherMiddleware;
use Rxak\Framework\Middleware\CsrfMiddleware;
use Rxak\Framework\Middleware\StartSessionMiddleware;
use Rxak\Framework\Middleware\TestMiddleware;
use Rxak\Framework\Models\User;
use Rxak\Framework\Routing\Route;
use Rxak\Framework\Routing\Router;
use Rxak\Framework\Validation\Validators\FeedbackValidator;

$router = Router::getInstance();

$homeRoutes = Route::group(
    [StartSessionMiddleware::class, CsrfMiddleware::class],
    [
        Route::get('/^\/$/', HomeController::class, 'home'),
        Route::post('/^\/test$/', HomeController::class, 'home'),
        Route::get('/^\/hello\/(.+)$/', HomeController::class, 'hello', ['mappers' => [User::class]])
    ]
);

$router->registerRoutes(
    Route::post(
        '/^\/contact$/',
        HomeController::class,
        'feedback',
        [
            'validator' => FeedbackValidator::class,
        ]
    ),
    ...$homeRoutes,
);