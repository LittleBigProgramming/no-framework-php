<?php

namespace App\Exceptions;

use App\Views\View;
use Exception;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;
use Zend\Diactoros\Response\RedirectResponse;
use App\Session\SessionStore;

class Handler
{
    protected $exception;
    protected $session;
    protected $response;
    protected $view;

    /**
     * Handler constructor.
     * @param Exception $exception
     * @param SessionStore $session
     * @param ResponseInterface $response
     * @param View $view
     */
    public function __construct(
        Exception $exception,
        SessionStore $session,
        ResponseInterface $response,
        View $view
    ) {
        $this->exception = $exception;
        $this->session = $session;
        $this->response = $response;
        $this->view = $view;
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

    protected function handleCsrfTokenException(Exception $e)
    {
        return $this->view->render($this->response, 'errors/csrf.twig');
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
