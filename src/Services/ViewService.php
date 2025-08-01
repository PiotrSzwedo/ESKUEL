<?php
namespace App\Services;

use Smarty\Exception;
use Smarty\Smarty;
use Smarty\Template;

class ViewService
{
    private Smarty $smarty;
    private ?array $manifest = null;

    public function __construct()
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir(__DIR__ . '/../../resources/views/');
        $this->smarty->setCompileDir(__DIR__ . '/../../storage/templates_c/');
        $this->smarty->setCacheDir(__DIR__ . '/../../storage/cache/');
        $this->smarty->caching = Smarty::CACHING_LIFETIME_CURRENT;

        $this->smarty->registerPlugin('function', 'vite_entry', [$this, 'viteEntry']);
        $this->smarty->registerPlugin('function', 'style_css', [$this, 'cssStyle']);
    }

    public function render(string $template, array $data = []): string
    {
        global $routerConfig;
        $data['prefix'] = $routerConfig['prefix'];
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }
        try {
            return $this->smarty->fetch($template);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function viteEntry(array $params, Template $smarty): string
    {
        global $routerConfig;

        if (!isset($params['file'])) {
            return '<!-- vite_entry: missing file param -->';
        }

        if ($this->manifest === null) {
            $manifestPath = __DIR__ . '/../../resources/js/.vite/manifest.json';

            if (!file_exists($manifestPath)) {
                return '<!-- vite_entry: manifest.json not found -->';
            }

            $this->manifest = json_decode(file_get_contents($manifestPath), true);
        }

        $file = $params['file'];

        if (!isset($this->manifest[$file])) {
            return "<!-- vite_entry: file '{$file}' not found in manifest -->";
        }

        $entry = $this->manifest[$file];
        $tags = '';

        if (!empty($entry['css'])) {
            foreach ($entry['css'] as $cssFile) {
                $tags .= '<link rel="stylesheet" href="/resources/js/' . $cssFile . '">' . PHP_EOL;
            }
        }

        $tags .= '<script type="module" src="'. $routerConfig["prefix"].$routerConfig['link_serving_the_built_frontend'] . '?file=' . $entry['file'] . '"></script>';

        return $tags;
    }

    public function cssStyle(array $params, Template $smarty): string
    {
        if (!isset($params['file'])) {
            return '<!-- style: missing file param -->';
        }

        global $routerConfig;

        $uri =  $routerConfig["prefix"] .  $routerConfig['link_serving_the_css_files']. '?file=' . $params['file'];

        return '<link rel="stylesheet" href="' . $uri . '">';
    }
}