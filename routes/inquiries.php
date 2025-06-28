<?php
use App\Controllers\Inquiries\EskuelController as InquiriesEskuelController;
use App\Controllers\Inquiries\DatabaseConnectionController;
use App\Controllers\Inquiries\DatabaseController as InquiriesDatabaseController;
use App\Controllers\Inquiries\FormController as InquiriesFormController;
use App\Middleware\DatabaseConnectionMiddleware;

global $routerConfig;
global $router;

$router->post("/eskuel/execute", [InquiriesEskuelController::class, 'execute'], [DatabaseConnectionMiddleware::class]);
$router->get("/eskuel/keywords", [InquiriesEskuelController::class, 'show']);
$router->get("/disconnect", [InquiriesDatabaseController::class, 'disconnect'], [DatabaseConnectionMiddleware::class]);
$router->post('/db/execute', [DatabaseConnectionController::class, 'executeSQL'], [DatabaseConnectionMiddleware::class]);
$router->post('/form', [InquiriesDatabaseController::class, 'store']);
$router->get('/databases-list', [InquiriesDatabaseController::class, 'show']);
$router->post('/database-conn', [DatabaseConnectionController::class, 'store']);
$router->patch('/database', [InquiriesFormController::class, 'update']);