<?php

namespace App\Controllers\Inquiries;

use App\Controllers\Controller;
use App\Services\DbWriteService;
use App\Services\ViewService;
use Core\Request;
use Core\Response;

class FormController extends Controller
{
    private DbWriteService $dbWriter;

    public function __construct(DbWriteService $dbWriter, ViewService $view){
        $this->dbWriter = $dbWriter;
        parent::__construct($view);
    }

    public function store(Request $request) :string
    {
        if ($request->method() != 'POST') {
            return Response::json(["success" => false], 400);
        }

        $isFileSaved = $this->dbWriter->writeDatabase(
            $request->input('host', 'localhost'),
            $request->input('database', 'database'),
            $request->input('username', 'root'),
            $request->input('password', ''),
            $request->input('port', 3310),
        );

        if ($isFileSaved){
            return Response::json(["success" => true], 200);
        }

        return Response::json(["success" => false], 500);
    }

    public function update(Request $request) :string{
        $host = $request->input('host', null);
        $database = $request->input('database', null);
        $username = $request->input('username', null);
        $password = $request->input('password', '');
        $port = $request->input('port', null);
        $id = $request->input('id', null);

        if ($host == null || $database == null || $username == null || $port == null || $id == null) {
            return Response::json(["success" => false, "message" => "Missing required fields"], 422);
        }

        $isFileSaved =  $this->dbWriter->updateDatabase($id . ".json", $host, $database, $username, $password, $port);

        if ($isFileSaved){
            $database = $this->dbWriter->readDatabase($id . ".json");
            return Response::json(["success" => true, "database" => $database], 200);
        }

        return Response::json(["success" => false], 500);
    }
}