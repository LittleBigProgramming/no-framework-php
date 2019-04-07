<?php

namespace App\Middleware;

use App\Auth\Auth;

class Authentication
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
        if ($this->auth->hasUserInSession()) {
            try {
                $this->auth->setUserFromSession();
            } catch (\Exception $e) {
                $this->auth->logout();
            }
        }

        return $next($request, $response);
    }
}
