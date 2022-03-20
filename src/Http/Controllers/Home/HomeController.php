<?php

namespace Rxak\Framework\Http\Controllers\Home;

use Exception;
use Rxak\Framework\Http\Controllers\BaseController;
use Rxak\Framework\Http\Request;
use Rxak\Framework\Http\Response;
use Rxak\Framework\Templating\Templates\HomePage;

class HomeController extends BaseController
{
    public function home(Request $request)
    {
        return new Response(
            new HomePage('Hello world!'),
            200
        );
    }

    public function hello(Request $request, string $next)
    {
        throw new Exception();
    }

    public function feedback(Request $request)
    {
        throw new \Exception('Hello there');
    }
}