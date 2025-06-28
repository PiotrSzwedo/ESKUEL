<?php

namespace App\Middleware;

use App\Services\DbServices;
use App\Services\ViewService;
use Core\Response;

class DatabaseAccessMiddleware extends Middleware implements MiddlewareInterface
{
    private DbServices $dbService;

    public function __construct(DbServices $dbService, ViewService $viewService)
    {
        $this->dbService = $dbService;
        parent::__construct($viewService);
    }
    public function handle()
    {
        if ($this->dbService->isDatabaseConnected()) {
            return null;
        }

        return Response::json([
            "message" => "Access denied.",
        ], 403);
    }
}