<?php

namespace Core;

use App\Services\ViewService;
use Exception;
use ReflectionMethod;

class Router
{
    private string $prefix;
    private ViewService $viewService;

    protected array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];

    protected array $middleware = []; // Globalne middleware
    protected array $routeMiddleware = []; // Middleware per trasa

    public function __construct(ViewService $viewService, string $prefix = "/eskuelmyadmin"){
        $this->viewService = $viewService;
        $this->prefix = $prefix;
    }

    private function normalizeUri(string $uri): string
    {
        $uri = preg_replace('#/+#', '/', $uri);
        $uri = str_replace($this->prefix, '', $uri);
        return $uri !== '/' ? rtrim($uri, '/') : $uri;
    }

    public function get($uri, $action, array $middleware = []): void
    {
        $this->addRoute('GET', $uri, $action, $middleware);
    }

    public function post($uri, $action, array $middleware = []): void
    {
        $this->addRoute('POST', $uri, $action, $middleware);
    }

    public function put($uri, $action, array $middleware = []): void
    {
        $this->addRoute('PUT', $uri, $action, $middleware);
    }

    public function patch($uri, $action, array $middleware = []): void
    {
        $this->addRoute('PATCH', $uri, $action, $middleware);
    }

    public function delete($uri, $action, array $middleware = []): void
    {
        $this->addRoute('DELETE', $uri, $action, $middleware);
    }

    private function addRoute(string $method, string $uri, $action, array $middleware = []): void
    {
        $normalized = $this->normalizeUri($uri);
        $this->routes[$method][$normalized] = ["class" => $action[0], "method" => $action[1]];
        $this->routeMiddleware[$method][$normalized] = $middleware;
    }

    public function addGlobalMiddleware(string $middlewareClass): void
    {
        $this->middleware[] = $middlewareClass;
    }

    public function dispatch($uri, $method)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = $this->normalizeUri($uri);

        if (!isset($this->routes[$method][$uri])) {
            return $this->viewService->render("errors/404.tpl");
        }

        foreach ($this->middleware as $middlewareClass) {
            $this->runMiddleware($middlewareClass);
        }

        foreach ($this->routeMiddleware[$method][$uri] ?? [] as $middlewareClass) {
            $this->runMiddleware($middlewareClass);
        }

        $route = $this->routes[$method][$uri];
        $controllerInstance = Container::make($route['class']);

        if (!method_exists($controllerInstance, $route['method'])) {
            throw new Exception("Metoda {$route['method']} nie istnieje w klasie {$route['class']}");
        }

        $methodReflection = new ReflectionMethod($controllerInstance, $route['method']);
        $args = [];

        foreach ($methodReflection->getParameters() as $param) {
            $paramClass = $param->getType()?->getName();
            if ($paramClass && class_exists($paramClass)) {
                $args[] = Container::make($paramClass);
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                throw new Exception("Nie można rozwiązać parametru: {$param->getName()}");
            }
        }

        return $methodReflection->invokeArgs($controllerInstance, $args);
    }

    private function runMiddleware(string $middlewareClass): void
    {
        if (!class_exists($middlewareClass)) {
            throw new Exception("Middleware {$middlewareClass} nie istnieje.");
        }

        $instance = Container::make($middlewareClass);

        if (!method_exists($instance, 'handle')) {
            throw new Exception("Middleware {$middlewareClass} musi implementować metodę handle.");
        }

        $response = $instance->handle();

        if ($response !== null) {
            echo $response;
            exit;
        }
    }

    public function redirect(string $uri): void
    {
        header("Location: $this->prefix/$uri");
        exit;
    }
}