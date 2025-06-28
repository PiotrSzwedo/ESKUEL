<?php
use App\Controllers\Inquiries\EskuelController as InquiriesEskuelController;
use App\Controllers\Inquiries\DatabaseConnectionController;
use App\Controllers\Inquiries\DatabaseController as InquiriesDatabaseController;
use App\Controllers\Inquiries\FormController as InquiriesFormController;
use App\Middleware\DatabaseAccessMiddleware;

global $routerConfig;
global $router;

$router->post("/eskuel/execute", [InquiriesEskuelController::class, 'execute'], [DatabaseAccessMiddleware::class]);
$router->get("/eskuel/keywords", [InquiriesEskuelController::class, 'show']);
$router->get("/disconnect", [InquiriesDatabaseController::class, 'disconnect'], [DatabaseAccessMiddleware::class]);
$router->post('/db/execute', [DatabaseConnectionController::class, 'executeSQL'], [DatabaseAccessMiddleware::class]);
$router->post('/form', [InquiriesDatabaseController::class, 'store']);
$router->get('/databases-list', [InquiriesDatabaseController::class, 'show']);
$router->post('/database-conn', [DatabaseConnectionController::class, 'store']);
$router->patch('/database', [InquiriesFormController::class, 'update']);