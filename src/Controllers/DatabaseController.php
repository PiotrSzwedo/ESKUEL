<?php

namespace App\Controllers;

use App\Services\DbServices;
use App\Services\DbWriteService;
use App\Services\ViewService;
use Core\Request;
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

    public function store(Request $request): string
    {

        if ($request->method() != 'POST') {
            return Response::json(["success" => false], 400);
        }

        $host = $request->input('host', null);
        $database = $request->input('database', null);
        $username = $request->input('username', null);
        $password = $request->input('password', '');
        $port = $request->input('port', null);
        $id = $request->input('id', null);

        if ($host == null || $database == null || $username == null || $port == null || $id == null) {
            return Response::json(["success" => false, "message" => "Missing required fields"], 422);
        }

        $config = $this->dbWriter->readDatabase($id . ".json");

        if (empty($config)) {
            return Response::json(["success" => false, "message" => "Database not found"], 404);
        }

        if ($config["host"] != $host || $config["database"] != $database || $config["username"] != $username || $config["port"] != $port)
            return Response::json(["success" => false, "message" => "Invalid parameters"], 422);

        if (trim($config["password"]) != "") {
            if (!password_verify($password, $config["password"]))
                return Response::json(["success" => false, "message" => "Wrong password"], 403);
        }

        $success = $this->dbService->connect(
            $host,
            $database,
            $username,
            $password,
            $port
        );

        if ($success) {
            return Response::json(["success" => true], 200);
        }else{
            return Response::json(["success" => false, "message" => "connection error"], 500);
        }
    }
}