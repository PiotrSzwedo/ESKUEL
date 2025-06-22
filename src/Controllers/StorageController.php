<?php

namespace App\Controllers;

use Core\Request;
use Core\Response;

class StorageController extends Controller
{
    public function index(Request $request){
        $file  = $request->input("file", null);

        $filePath = __DIR__ . "/../../storage/public/" . $file;

        if (file_exists($filePath)) {
            return Response::file($filePath);
        }

        return Response::html($this->viewService->render("errors/404.tpl"));
    }
}