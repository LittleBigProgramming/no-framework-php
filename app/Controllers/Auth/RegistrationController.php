<?php

namespace App\Controllers\Auth;

use App\Auth\Auth;
use App\Auth\Hashing\HashingInterface;
use App\Controllers\Controller;
use App\Models\User;
use App\Views\View;
use Doctrine\ORM\EntityManager;
use League\Route\RouteCollection;

class RegistrationController extends Controller
{
    protected $view;
    protected $hash;
    protected $route;
    protected $database;
    protected $auth;

    /**
     * RegistrationController constructor.
     * @param View $view
     * @param HashingInterface $hash
     * @param RouteCollection $route
     * @param EntityManager $database
     * @param Auth $auth
     */
    public function __construct(
        View $view,
        HashingInterface $hash,
        RouteCollection $route,
        EntityManager $database,
        Auth $auth
    ) {
        $this->view = $view;
        $this->hash = $hash;
        $this->route = $route;
        $this->database = $database;
        $this->auth = $auth;
    }

    /**
     * @param $request
     * @param $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index($request, $response)
    {
        return $this->view->render($response, 'auth/register.twig');
    }

    /**
     * @param $request
     * @param $response
     * @return \Zend\Diactoros\Response\RedirectResponse
     * @throws \App\Exceptions\ValidationException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function register($request, $response)
    {
        $data = $this->validateRegistration($request);
        $user = $this->createUser($data);

        if (!$this->auth->attempt($data['email'], $data['password'])) {
            return redirect($this->route->getNamedRoute('home')->getPath());
        }
        
        return redirect($this->route->getNamedRoute('home')->getPath());
    }

    /**
     * @param $data
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function createUser($data)
    {
        $user = new User;

        $user->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $this->hash->create($data['password'])
        ]);

        $this->database->persist($user);
        $this->database->flush();

        return $user;
    }

    /**
     * @param $request
     * @return mixed
     * @throws \App\Exceptions\ValidationException
     */
    protected function validateRegistration($request)
    {
        return $this->validate($request, [
            'email' => ['required', 'email', ['exists', User::class]],
            'name' => ['required'],
            'password' => ['required'],
            'password_confirmation' => ['required', ['equals', 'password']],
        ]);
    }
}
