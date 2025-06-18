<?php

namespace App\Controllers;

use App\Services\DbWriteService;
use App\Services\ViewService;
use Core\Response;

class DatabaseController extends Controller
{
    protected DbWriteService $dbWriter;

    public function __construct(DbWriteService $dbWriter, ViewService $view){
        $this->dbWriter = $dbWriter;

        parent::__construct($view);
    }

    public function index(): string
    {
        return Response::html($this->viewService->render("databases.tpl"));
    }

    public function show(){
        $databases = $this->dbWriter->getDatabases(0, 30);

        return Response::json([
            "databases" => $databases,
        ], 200);
    }
}