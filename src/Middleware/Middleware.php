<?php

namespace App\Middleware;

use App\Services\ViewService;
use Closure;
use Core\Response;

abstract class Middleware
{
    private ViewService $view;

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function forbidden(): string
    {
        return Response::html($this->view->render('errors/403.tpl'));
    }
}