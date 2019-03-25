<?php

namespace App\Controllers\Auth;

use App\Controllers\Controller;
use App\Views\View;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginController extends Controller
{
    protected $view;

    public function __construct(View $view)
    {
        $this->view = $view;
    }

    public function index(RequestInterface $request, ResponseInterface $response)
    {
        //return $this->view->render($response, 'auth/login.twig');
    }

    public function login($request, $response)
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);
    }
}
