<?php

namespace App\Controllers;

use Core\Request;
use Core\Response;

class FormController extends Controller
{
    public function show() : Response
    {
        return Response::html($this->viewService->render('form.tpl'));
    }

    public function store(Request $request) : false|string
    {
        if ($request->method() === 'POST') {
            return Response::json(["success" => false], 400);
        }

        $data = [
            $request->input('port', 3310),
            $request->input('host', 'localhost'),
            $request->input('username', 'root'),
            $request->input('password', ''),
            $request->input('database', 'database'),
        ];

        return Response::json(["success" => true], 200);
    }
}