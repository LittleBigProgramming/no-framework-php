<?php

namespace App\Exceptions;

use Exception;
use ReflectionClass;
use Zend\Diactoros\Response\RedirectResponse;
use App\Session\SessionStore;

class Handler
{
    protected $exception;
    protected $session;

    /**
     * Handler constructor.
     * @param Exception $exception
     * @param SessionStore $session
     */
    public function __construct(
        Exception $exception,
        SessionStore $session
    ) {
        $this->exception = $exception;
        $this->session = $session;
    }

    /**
     * @throws \ReflectionException
     * @throws Exception
     */
    public function respond()
    {
        $class = (new ReflectionClass($this->exception))->getShortName();

        if (method_exists($this, $method = "handle{$class}")) {
            return $this->{$method}($this->exception);
        }

        return $this->unhandledException($this->exception);
    }

    /**
     * @param Exception $e
     * @return \RedirectResponse
     */
    protected function handleValidationException(Exception $e)
    {
        $this->session->set([
            'errors' => $e->getErrors(),
            'old' => $e->getOldInput()
        ]);

        return redirect($e->getPath());
    }

    /**
     * @param Exception $e
     * @throws Exception
     */
    protected function unhandledException(Exception $e)
    {
        throw $e;
    }
}
