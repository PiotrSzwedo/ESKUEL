<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/routes/web.php';

$configDbPath = __DIR__ . '/config/db.php';
$path = $_SERVER['REQUEST_URI'];

echo $router->dispatch($path, $_SERVER['REQUEST_METHOD']);