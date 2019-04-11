<?php

namespace App\Middleware;

use App\Exceptions\CsrfTokenException;
use App\Security\Csrf;

class CsrfGuard
{
    protected $csrf;

    /**
     * CsrfGuard constructor.
     * @param Csrf $csrf
     */
    public function __construct(Csrf $csrf)
    {
        $this->csrf = $csrf;
    }

    /**
     * @param $request
     * @param $response
     * @param callable $next
     * @return mixed
     * @throws CsrfTokenException
     */
    public function __invoke($request, $response, callable $next)
    {
        if (!$this->requestRequiresProtection($request)) {
            return $next($request, $response);
        }

        if (!$this->csrf->tokenIsValid($this->getTokenFromRequest($request))) {
            throw new CsrfTokenException();
        }

        return $next($request, $response);
    }

    /**
     * @param $request
     * @return null
     */
    protected function getTokenFromRequest($request)
    {
        return $request->getParsedBody()[$this->csrf->key()] ?? null;
    }

    /**
     * @param $request
     * @return bool
     */
    protected function requestRequiresProtection($request) : bool
    {
        return in_array($request->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH']);
    }
}
