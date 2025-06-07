<?php
namespace App\Services;

use Smarty\Smarty;

class ViewService
{
    private Smarty $smarty;

    public function __construct()
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir(__DIR__ . '/../../views/');
        $this->smarty->setCompileDir(__DIR__ . '/../../storage/templates_c/');
        $this->smarty->setCacheDir(__DIR__ . '/../../storage/cache/');
        $this->smarty->caching = Smarty::CACHING_LIFETIME_CURRENT;
    }

    public function render(string $template, array $data = []): string
    {
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        return $this->smarty->fetch($template);
    }
}