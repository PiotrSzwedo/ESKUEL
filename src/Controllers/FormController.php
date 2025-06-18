<?php

namespace App\Controllers;

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
    public function show() : string
    {
        return Response::html($this->viewService->render('form.tpl'));
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
}