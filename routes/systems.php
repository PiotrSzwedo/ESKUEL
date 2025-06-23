<?php

global $routerConfig;

$router->get('/storage', [\App\Controllers\StorageController::class, 'index']);
$router->get($routerConfig['link_serving_the_built_frontend'], [\App\Controllers\StorageController::class, 'vite']);