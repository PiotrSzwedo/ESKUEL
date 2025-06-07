<?php

require __DIR__ . '/vendor/autoload.php';

$configDbPath = __DIR__ . '/config/db.php';

if (!file_exists($configDbPath)) {
    include __DIR__ . '/views/setup.php';
    die();
}

$db = require_once $configDbPath;

require __DIR__ . '/routes/web.php';

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);