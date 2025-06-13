<?php

namespace Core;

use App\Services\ViewService;
use Exception;
use ReflectionMethod;

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
        $this->routes['GET'][$normalized] = ["class" => $action[0], "method" => $action[1]];
    }


    public function post($uri, $action): void
    {
        $normalized = $this->normalizeUri($uri);
        $this->routes['POST'][$normalized] = ["class" => $action[0], "method" => $action[1]];
    }

    public function put($uri, $action): void{
        $normalized = $this->normalizeUri($uri);
        $this->routes['PUT'][$normalized] = ["class" => $action[0], "method" => $action[1]];
    }

    public function patch($uri, $action): void{
        $normalized = $this->normalizeUri($uri);
        $this->routes['PATCH'][$normalized] = ["class" => $action[0], "method" => $action[1]];
    }

    public function delete($uri, $action): void{
        $normalized = $this->normalizeUri($uri);
        $this->routes['DELETE'][$normalized] = ["class" => $action[0], "method" => $action[1]];
    }

    public function dispatch($uri, $method)
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = $this->normalizeUri($uri);

        if (isset($this->routes[$method][$uri])) {
            $route = $this->routes[$method][$uri];
            $controllerClass = $route['class'] ?? null;
            $controllerMethod = $route['method'] ?? null;

            if (!$controllerClass || !class_exists($controllerClass)) {
                throw new Exception("Kontroler {$controllerClass} nie istnieje.");
            }

            $controllerInstance = Container::make($controllerClass);
            if (!method_exists($controllerInstance, $controllerMethod)) {
                throw new Exception("Metoda {$controllerMethod} nie istnieje w klasie {$controllerClass}");
            }

            $methodReflection = new ReflectionMethod($controllerInstance, $controllerMethod);
            $params = $methodReflection->getParameters();

            $args = [];
            foreach ($params as $param) {
                $paramClass = $param->getType()?->getName();

                if ($paramClass && class_exists($paramClass)) {
                    $args[] = \Core\Container::make($paramClass);
                } elseif ($param->isDefaultValueAvailable()) {
                    $args[] = $param->getDefaultValue();
                } else {
                    throw new Exception("Nie można rozwiązać parametru: {$param->getName()}");
                }
            }

            try {
                return $methodReflection->invokeArgs($controllerInstance, $args);
            } catch (\ReflectionException $e) {
                throw new Exception($e->getMessage());
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
