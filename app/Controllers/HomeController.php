<?php

namespace App\Controllers;

use App\Views\View;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeController
{
    protected $view;
    protected $auth;

    /**
     * HomeController constructor.
     * @param View $view
     * @param Auth $auth
     */
    public function __construct(View $view)
    {
        $this->view = $view;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $this->view->render($response, 'home.twig');
    }
}
