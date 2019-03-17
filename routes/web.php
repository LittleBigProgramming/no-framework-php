<?php

use App\Controllers\HomeController;

$route->get('/', HomeController::class . '::index')->setName('home');