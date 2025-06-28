<?php

namespace App\Middleware;

use App\Services\DbServices;
use App\Services\ViewService;

class DatabaseConnectionMiddleware extends Middleware implements MiddlewareInterface
{
    private DbServices $dbService;

    public function __construct(DbServices $dbService, ViewService $viewService)
    {
        $this->dbService = $dbService;
        parent::__construct($viewService);
    }

    public function handle(): ?string
    {
        if ($this->dbService->isDatabaseConnected()) {
            return null;
        }

        return $this->forbidden();
    }
}