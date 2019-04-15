<?php

use App\Controllers\DashboardController;
use App\Controllers\HomeController;
use App\Middleware\Authenticated;

$route->get('/', HomeController::class . '::index')->setName('home');

$route->group('', function ($route) {
    $route->get('/dashboard', DashboardController::class . '::index')->setName('dashboard');
})->middleware($container->get(Authenticated::class));

$route->group('/auth', function ($route) {
    $route->get('/login', 'App\Controllers\Auth\LoginController::index')->setName('auth.login');
    $route->post('/login', 'App\Controllers\Auth\LoginController::login');
    $route->post('/logout', 'App\Controllers\Auth\LogoutController::logout')->setName('auth.logout');


    $route->get('/register', 'App\Controllers\Auth\RegistrationController::index')->setName('auth.register');
    $route->post('/register', 'App\Controllers\Auth\RegistrationController::register');
});
