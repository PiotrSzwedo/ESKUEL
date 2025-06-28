<?php
namespace App\Controllers\Views;

use App\Controllers\Controller;
use App\Services\DbServices;
use App\Services\ViewService;
use Core\Response;

class HomeController extends Controller
{
    protected DbServices $dbServices;
    public function __construct(DbServices $dbServices, ViewService $viewService){
        $this->dbServices = $dbServices;
        parent::__construct($viewService);
    }

    public function index(): string
    {
        if (!$this->dbServices->isDatabaseConnected()) {
            return Response::html($this->viewService->render("databases.tpl"));
        }

        return $this->viewService->render('sql.tpl');
    }
}