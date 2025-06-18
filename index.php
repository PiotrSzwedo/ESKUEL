<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/routes/web.php';

$configDbPath = __DIR__ . '/config/db.php';
$path = $_GET['path'] ?? '/';

    if ($path != "/form"){
        $router->redirect('/form');
    }


echo $router->dispatch($path, $_SERVER['REQUEST_METHOD']);