<?php

namespace App\Controllers;

use Core\Request;
use Core\Response;

class StorageController extends Controller
{
    public function index(Request $request): ?string
    {
        $file  = $request->input("file", null);

        $filePath = __DIR__ . "/../../storage/public/" . $file;

        if (file_exists($filePath)) {
            return Response::file($filePath);
        }

        return Response::html($this->viewService->render("errors/404.tpl"));
    }

    public function vite(Request $request): ?string
    {
        $file  = $request->input("file", null);

        $filePath = __DIR__ . "/../../resources/js/" . $file;

        if (file_exists($filePath)) {
            return Response::js($filePath);
        }

        return Response::html($this->viewService->render("errors/404.tpl"));
    }

    public function css(Request $request): ?string{
        $file  = $request->input("file", null);

        $filePath = __DIR__ . "/../../resources/css/" . $file;

        if (file_exists($filePath)) {
            return Response::css($filePath);
        }

        return Response::html($this->viewService->render("errors/404.tpl"));
    }
}