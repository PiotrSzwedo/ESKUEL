<?php
namespace App\Controllers;

use App\Services\ViewService;
use Core\Response;

class HomeController
{
    private ViewService $view;

    public function __construct()
    {
        $this->view = new ViewService();
    }

    public function index()
    {
        $data = [
            'title' => 'Witamy w mojej aplikacji',
            'items' => ['Jeden', 'Dwa', 'Trzy']
        ];

        return $this->view->render('home.tpl', $data);
    }
}