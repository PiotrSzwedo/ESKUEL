<?php

use Core\Router;
$router = \Core\Container::make(Router::class);

$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/form', [\App\Controllers\FormController::class, 'show']);
$router->post('/form', [\App\Controllers\FormController::class, 'store']);
$router->get('/databases', [\App\Controllers\DatabaseController::class, 'index']);
$router->get('/databases-list', [\App\Controllers\DatabaseController::class, 'show']);