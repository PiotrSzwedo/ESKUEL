<?php
namespace App\Controllers;

use App\Services\ViewService;
use Core\Response;

class HomeController extends Controller
{

    public function index()
    {
        $data = [
            'title' => 'Witamy w mojej aplikacji',
            'items' => ['Jeden', 'Dwa', 'Trzy']
        ];

        return $this->viewService->render('home.tpl', $data);
    }
}