<?php

use Core\Router;
$router = \Core\Container::make(Router::class);

$router->get('/', 'HomeController@index');