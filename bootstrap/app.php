<?php

session_start();

require_once __DIR__ . '/../vendor/autoload.php';

try {
    $dotenv = (new Dotenv\Dotenv(base_path()))->load();
} catch (DotEnv\Exception\InvalidFileException $e) {
    echo $e;
}

require_once base_path('/bootstrap/container.php');

$container->get('config')->get('app.name');

$route = $container->get(\League\Route\RouteCollection::class);

require_once base_path('routes/web.php');

try {
    $response = $route->dispatch(
        $container->get('request'),
        $container->get('response')
    );
} catch (Exception $e) {
    $handler = new App\Exceptions\Handler($e);

    $response = $handler->respond();
}
