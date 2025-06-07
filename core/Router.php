<?php

namespace Core;

use App\Services\ViewService;

class Router
{
    private ViewService $viewService;

    public function __construct(ViewService $viewService){
        $this->viewService = $viewService;
    }
    private string $prefix = "/eskuelmyadmin";
    protected array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];

    private function normalizeUri(string $uri): string
    {
        $uri = preg_replace('#/+#', '/', $uri);


        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        return $uri;
    }

    public  function get($uri, $action): void
    {
        $normalized = $this->normalizeUri($uri);
        $this->routes['GET'][$normalized] = $action;
    }


    public function post($uri, $action): void
    {
        $normalized = $this->normalizeUri($uri);
        $this->routes['POST'][$normalized] = $normalized;
    }

    public function put($uri, $action): void{
        $normalized = $this->normalizeUri($uri);
        $this->routes['PUT'][$normalized] = $action;
    }

    public function patch($uri, $action): void{
        $normalized = $this->normalizeUri($uri);
        $this->routes['PATCH'][$normalized] = $action;
    }

    public function delete($uri, $action): void{
        $normalized = $this->normalizeUri($uri);
        $this->routes['DELETE'][$normalized] = $action;
    }

    public function dispatch($uri, $method)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = $this->normalizeUri($uri);

        if (isset($this->routes[$method][$uri])) {
            [$controller, $method] = explode('@', $this->routes[$method][$uri]);
            $controllerClass = "App\\Controllers\\{$controller}";

            if (class_exists($controllerClass)) {
                $instance = Container::make($controllerClass);
                if (method_exists($instance, $method)) {
                    return $instance->$method();
                }
            }
        }

        return $this->viewService->render("errors/404.tpl");
    }

    public function redirect(string $uri) :void
    {
        header("Location: $this->prefix/index.php?path=$uri");
        exit;
    }
}
