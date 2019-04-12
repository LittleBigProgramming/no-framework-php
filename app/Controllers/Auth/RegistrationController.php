<?php

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Controllers\Controller;
use App\Models\User;
use App\Session\Flash;
use App\Views\View;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RegistrationController extends Controller
{
    protected $view;
    protected $auth;


    public function __construct(
        View $view
    ) {
        $this->view = $view;
    }

    /**
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function index(RequestInterface $request, ResponseInterface $response)
    {
        return $this->view->render($response, 'auth/register.twig');
    }

    public function register($request, $response)
    {
        $data = $this->validateRegistration($request);
    }

    protected function validateRegistration(RequestInterface $request)
    {
        return $this->validate($request, [
            'email' => ['required', 'email', ['exists', User::class]],
            'name' => ['required'],
            'password' => ['required'],
            'confirm_password' => ['required', ['equals', 'password']]
        ]);
    }
}
