<?php

use App\Controllers\DatabaseConnectionController;
use App\Controllers\DatabaseController;
use App\Controllers\FormController;
use Core\Router;

$routerConfig = include __DIR__ . "/../config/router.php";

$router = new Router(new \App\Services\ViewService(), $routerConfig["prefix"] ?: "/eskuelmyadmin", $routerConfig["path_with_redirection_to_index"] ?: true);

$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/form', [FormController::class, 'show']);
$router->post('/form', [FormController::class, 'store']);
$router->get('/databases', [DatabaseController::class, 'index']);
$router->get('/sql', [DatabaseController::class, 'sqlShow']);
$router->get('/storage', [\App\Controllers\StorageController::class, 'index']);


$router->get('/databases-list', [DatabaseController::class, 'show']);
$router->post('/database-conn', [DatabaseConnectionController::class, 'store']);
$router->patch('/database', [FormController::class, 'update']);