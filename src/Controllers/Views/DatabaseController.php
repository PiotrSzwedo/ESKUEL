<?php

namespace App\Controllers\Views;

use App\Controllers\Controller;
use App\Services\DbServices;
use App\Services\DbWriteService;
use App\Services\ViewService;
use Core\Response;

class DatabaseController extends Controller
{
    protected DbWriteService $dbWriter;
    protected DbServices $dbService;

    protected \PDO $PDO;

    public function __construct(DbWriteService $dbWriter, ViewService $view, DbServices $dbServices){
        $this->dbWriter = $dbWriter;
        $this->dbService = $dbServices;

        parent::__construct($view);
    }

    public function index(): string
    {
        return Response::html($this->viewService->render("databases.tpl"));
    }


    public function sqlShow(): string{
        return Response::html($this->viewService->render("sql.tpl"));
    }
}