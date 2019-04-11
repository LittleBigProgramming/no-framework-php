<?php

use App\Controllers\HomeController;

$route->get('/', HomeController::class . '::index')->setName('home');

$route->group('/auth', function ($route) {
    $route->get('/login', 'App\Controllers\Auth\LoginController::index')->setName('auth.login');
    $route->post('/login', 'App\Controllers\Auth\LoginController::login');
    $route->post('/logout', 'App\Controllers\Auth\LogoutController::logout')->setName('auth.logout');
});
