<?php

namespace App\Controllers;

use App\Cookie\CookieJar;
use App\Views\View;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HomeController
{
    protected $view;
    protected $cookie;

    /**
     * HomeController constructor.
     * @param View $view
     * @param CookieJar $cookie
     */
    public function __construct(View $view, CookieJar $cookie)
    {
        $this->view = $view;
        $this->cookie = $cookie;
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
