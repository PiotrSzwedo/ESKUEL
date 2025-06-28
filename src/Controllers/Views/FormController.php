<?php

namespace App\Controllers\Views;

use App\Controllers\Controller;
use App\Services\DbWriteService;
use App\Services\ViewService;
use Core\Response;

class FormController extends Controller
{
    private DbWriteService $dbWriter;

    public function __construct(DbWriteService $dbWriter, ViewService $view){
        $this->dbWriter = $dbWriter;
        parent::__construct($view);
    }
    public function show() : string
    {
        return Response::html($this->viewService->render('form.tpl'));
    }
}