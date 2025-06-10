<?php

namespace App\Controllers;

use App\Services\ViewService;

abstract class Controller
{
    protected ViewService $viewService;

    public function __construct(ViewService $view){
        $this->viewService = $view;
    }
}