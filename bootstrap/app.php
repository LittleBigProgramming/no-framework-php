<?php

session_start();

require_once  __DIR__ . '/../vendor/autoload.php';

try {
    $dotenv = Dotenv\Dotenv::create(__DIR__ . '/../')->load();
} catch (DotEnv\Exception\InvalidFileException $e) {
    echo $e;
}

require_once __DIR__ . '/container.php';
