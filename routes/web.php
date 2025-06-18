<?php

use Core\Router;
$router = \Core\Container::make(Router::class);

$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/form', [\App\Controllers\FormController::class, 'show']);