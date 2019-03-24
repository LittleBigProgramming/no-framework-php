<?php

namespace App\Exceptions;

use Exception;
use ReflectionClass;
use Zend\Diactoros\Response\RedirectResponse;

class Handler
{
    protected $exception;

    /**
     * Handler constructor.
     * @param Exception $exception
     */
    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @throws \ReflectionException
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
        // session set
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
