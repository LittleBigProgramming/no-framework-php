<?php

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Controllers\Controller;
use App\Session\Flash;
use App\Views\View;
use League\Route\RouteCollection;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class LoginController extends Controller
{
    protected $view;
    protected $auth;
    protected $route;
    protected $flash;

    /**
     * LoginController constructor.
     * @param View $view
     * @param Auth $auth
     * @param RouteCollection $route
     * @param Flash $flash
     */
    public function __construct(
        View $view,
        Auth $auth,
        RouteCollection $route,
        Flash $flash
    ) {
        $this->view = $view;
        $this->auth = $auth;
        $this->route = $route;
        $this->flash = $flash;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $this->view->render($response, 'auth/login.twig');
    }

    /**
     * @param $request
     * @param $response
     * @return \Zend\Diactoros\Response\RedirectResponse
     * @throws \App\Exceptions\ValidationException
     */
    public function login($request, $response)
    {
        $data = $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $attempt = $this->auth->attempt($data['email'], $data['password']);

        if (!$attempt) {
            $this->flash->now('error', 'Could not with sign in the the entered credentials.');

            return redirect($request->getUri()->getPath());
            dump('failed');
            die;
        }

        return redirect($this->route->getNamedRoute('home')->getPath());
    }
}
