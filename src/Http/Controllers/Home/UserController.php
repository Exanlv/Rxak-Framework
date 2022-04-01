<?php

namespace Rxak\Framework\Http\Controllers\Home;

use Rxak\Framework\Http\Controllers\BaseController;
use Rxak\Framework\Http\Request;
use Rxak\Framework\Http\Response;
use Rxak\Framework\Session\MessageBag;
use Rxak\Framework\Templating\Templates\HomePage;
use Rxak\Framework\Templating\Templates\UserRegisterPage;

class UserController extends BaseController
{
    public function register(Request $request)
    {
        return new Response(
            new UserRegisterPage('Hello world!'),
            200
        );
    }

    public function create(Request $request)
    {
        return new Response(
            new HomePage('Hello world!'),
            200
        );
    }
}