<?php

namespace App\Middleware;

use App\Auth\Auth;

class Guest
{
    protected $auth;

    /**
     * Authenticated constructor.
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param $request
     * @param $response
     * @param callable $next
     * @return \Zend\Diactoros\Response\RedirectResponse
     */
    public function __invoke($request, $response, callable $next)
    {
        return $this->auth->isLoggedIn() ? $response = redirect('/') : $next($request, $response);
    }
}
