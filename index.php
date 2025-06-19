<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/routes/web.php';

$configDbPath = __DIR__ . '/config/db.php';
if ($routerConfig["path_with_redirection_to_index"] || true){
    $path = $_SERVER['REQUEST_URI'];
}else{
    $path = $_GET['path'];
}

echo $router->dispatch($path, $_SERVER['REQUEST_METHOD']);