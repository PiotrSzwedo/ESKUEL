<?php

use Core\Router;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../routes/web.php';

$path = $_SERVER['REQUEST_URI'];

echo $router->dispatch($path, $_SERVER['REQUEST_METHOD']);