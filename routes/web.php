<?php

$route->get('/', function ($request, $response) {
    $response->getBody()->write('Home');

    return $response;
});