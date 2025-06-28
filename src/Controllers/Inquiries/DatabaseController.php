<?php

namespace App\Controllers\Inquiries;

use App\Controllers\Controller;
use App\Services\DbServices;
use App\Services\DbWriteService;
use App\Services\ViewService;
use Core\Response;

class DatabaseController extends Controller
{
    protected DbWriteService $dbWriter;
    protected DbServices $dbService;

    public function __construct(DbWriteService $dbWriter, ViewService $view, DbServices $dbServices){
        $this->dbWriter = $dbWriter;
        $this->dbService = $dbServices;

        parent::__construct($view);
    }
    public function show(): string
    {
        $databases = $this->dbWriter->getDatabases(0, 30);

        return Response::json([
            "databases" => $databases,
        ], 200);
    }

    public function disconnect(): void
    {
        global $router;

        if ($this->dbService->disconnect()){
            $router->redirect("/");
        }
    }
}