<?php

use Core\Router;

$routerConfig = include __DIR__."/../config/router.php";

$router = new Router(new \App\Services\ViewService(), $routerConfig["prefix"] ?: "/eskuelmyadmin", $routerConfig["path_with_redirection_to_index"] ?: true);

$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/form', [\App\Controllers\FormController::class, 'show']);
$router->post('/form', [\App\Controllers\FormController::class, 'store']);
$router->get('/databases', [\App\Controllers\DatabaseController::class, 'index']);
$router->get('/databases-list', [\App\Controllers\DatabaseController::class, 'show']);
$router->post('/database-conn', [\App\Controllers\DatabaseController::class, 'store']);
$router->patch('/database', [\App\Controllers\FormController::class, 'update']);