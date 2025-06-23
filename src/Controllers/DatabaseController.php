<?php

namespace App\Controllers;

use App\Services\DbServices;
use App\Services\DbWriteService;
use App\Services\ViewService;
use Core\Request;
use Core\Response;
use Core\Router;
use PDO;

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

    public function show(): string
    {
        $databases = $this->dbWriter->getDatabases(0, 30);

        return Response::json([
            "databases" => $databases,
        ], 200);
    }

    public function sqlShow(): string{
        return Response::html($this->viewService->render("sql.tpl"));
    }

    public function disconnect(): void
    {
        global $router;

        if ($this->dbService->disconnect()){
            $router->redirect("/");
        }
    }
}