<?php

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Controllers\Controller;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LogoutController extends Controller
{
    protected $auth;

    /**
     * LogoutController constructor.
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Zend\Diactoros\Response\RedirectResponse
     */
    public function logout(RequestInterface $request, ResponseInterface $response)
    {
        $this->auth->logout();

        return redirect('/');
    }
}
