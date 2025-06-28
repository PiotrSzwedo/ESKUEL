<?php

use App\Controllers\Views\StorageController;

global $routerConfig;
global $router;

$router->get('/storage', [StorageController::class, 'index']);
$router->get($routerConfig['link_serving_the_built_frontend'], [StorageController::class, 'vite']);
$router->get($routerConfig['link_serving_the_css_files'], [StorageController::class, 'css']);