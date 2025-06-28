<?php

use App\Controllers\Views\DatabaseController;
use App\Controllers\Views\FormController;
use App\Middleware\DatabaseConnectionMiddleware;
use Core\Router;

global $routerConfig;
global $router;
$routerConfig = include __DIR__ . "/../config/router.php";
$router = new Router(new \App\Services\ViewService(), $routerConfig["prefix"] ?: "/eskuelmyadmin");

$db = \Core\Container::make(\App\Services\DbServices::class);

$router->get('/', [\App\Controllers\Views\HomeController::class, 'index']);

$router->get('/sql', [DatabaseController::class, 'sqlShow'], [DatabaseConnectionMiddleware::class]);
$router->get("/eskuel", [DatabaseController::class, 'eskuelShow'], [DatabaseConnectionMiddleware::class]);


$router->get('/databases', [DatabaseController::class, 'index']);
$router->get('/form', [FormController::class, 'show']);


include __DIR__ . "/systems.php";
include __DIR__ . "/inquiries.php";