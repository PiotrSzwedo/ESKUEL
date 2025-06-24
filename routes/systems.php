<?php

use App\Controllers\StorageController;
global $routerConfig;

$router->get('/storage', [StorageController::class, 'index']);
$router->get($routerConfig['link_serving_the_built_frontend'], [StorageController::class, 'vite']);
$router->get($routerConfig['link_serving_the_css_files'], [StorageController::class, 'css']);