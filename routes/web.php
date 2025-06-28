<?php

use App\Controllers\Inquiries\DatabaseConnectionController;
use App\Controllers\Inquiries\DatabaseController as InquiriesDatabaseController;
use App\Controllers\Inquiries\FormController as InquiriesFormController;
use App\Controllers\Views\DatabaseController;
use App\Controllers\Views\FormController;
use App\Controllers\Inquiries\EskuelController as InquiriesEskuelController;
use Core\Router;

global $routerConfig;
global $router;
$routerConfig = include __DIR__ . "/../config/router.php";
$router = new Router(new \App\Services\ViewService(), $routerConfig["prefix"] ?: "/eskuelmyadmin");

$db = \Core\Container::make(\App\Services\DbServices::class);
$router->get('/', [\App\Controllers\Views\HomeController::class, 'index']);

if ($db->isDatabaseConnected()) {
    $router->get('/sql', [DatabaseController::class, 'sqlShow']);
    $router->get("/disconnect", [InquiriesDatabaseController::class, 'disconnect']);
    $router->post('/db/execute', [DatabaseConnectionController::class, 'executeSQL']);

    $router->get("/eskuel", [DatabaseController::class, 'eskuelShow']);

    $router->post("/eskuel/execute", [InquiriesEskuelController::class, 'execute']);
    $router->get("/eskuel/keywords", [InquiriesEskuelController::class, 'show']);
}

$router->get('/form', [FormController::class, 'show']);
$router->post('/form', [InquiriesDatabaseController::class, 'store']);
$router->get('/databases', [InquiriesDatabaseController::class, 'index']);
$router->get('/databases-list', [InquiriesDatabaseController::class, 'show']);
$router->post('/database-conn', [DatabaseConnectionController::class, 'store']);
$router->patch('/database', [InquiriesFormController::class, 'update']);


include __DIR__ . "/systems.php";