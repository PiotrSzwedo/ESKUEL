<?php
namespace App\Controllers;

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

    public function index()
    {
        if (!$this->dbServices->isDatabaseConnected()) {
            return Response::html($this->viewService->render("databases.tpl"));
        }

        return $this->viewService->render('home.tpl');
    }
}