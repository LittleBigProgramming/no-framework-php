<?php

namespace App\Middleware;

use App\Auth\Auth;

class AuthenticationFromCookie
{
    protected $auth;

    /**
     * Authentication constructor.
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
     * @return mixed
     */
    public function __invoke($request, $response, callable $next)
    {
        if ($this->auth->isLoggedIn()) {
            return $next($request, $response);
        }

        if ($this->auth->hasRecallerCookie()) {
            try {
                $this->auth->setUserFromCookie();
            } catch (\Exception $e) {
                $this->auth->logout();
            }
        }

        return $next($request, $response);
    }
}
