<?php

namespace App\Controllers;

use App\Auth\Auth;
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
    public function __construct(View $view, Auth $auth)
    {
        $this->view = $view;
        $this->auth = $auth;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $this->view->render($response, 'home.twig', [
         'user' => $this->auth->getUser()
        ]);
    }
}
