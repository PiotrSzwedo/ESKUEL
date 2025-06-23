<?php

use App\Controllers\DatabaseConnectionController;
use App\Controllers\DatabaseController;
use App\Controllers\FormController;
use Core\Router;

global $routerConfig;
$routerConfig = include __DIR__ . "/../config/router.php";

$router = new Router(new \App\Services\ViewService(), $routerConfig["prefix"] ?: "/eskuelmyadmin");

$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/form', [FormController::class, 'show']);
$router->post('/form', [FormController::class, 'store']);
$router->get('/databases', [DatabaseController::class, 'index']);
$router->get('/sql', [DatabaseController::class, 'sqlShow']);


$router->get('/databases-list', [DatabaseController::class, 'show']);
$router->post('/database-conn', [DatabaseConnectionController::class, 'store']);
$router->patch('/database', [FormController::class, 'update']);
$router->post('/db/execute', [DatabaseConnectionController::class, 'executeSQL']);

include __DIR__ . "/systems.php";