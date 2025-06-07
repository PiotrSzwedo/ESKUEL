<?php
namespace Core;

use ReflectionClass;
use Exception;
use ReflectionNamedType;

class Container
{
    protected static array $bindings = [];

    public static function bind(string $abstract, callable|string $concrete): void
    {
        self::$bindings[$abstract] = $concrete;
    }

    public static function make(string $class)
    {
        if (isset(self::$bindings[$class])) {
            $concrete = self::$bindings[$class];
            if (is_callable($concrete)) {
                return $concrete();
            }
            $class = $concrete;
        }

        try {
            $reflector = new ReflectionClass($class);


        if (!$reflector->isInstantiable()) {
            throw new Exception("Klasa $class nie jest możliwa do utworzenia.");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $class;
        }

        $parameters = $constructor->getParameters();
        $dependencies = self::resolveDependencies($parameters);

        return $reflector->newInstanceArgs($dependencies);

        } catch (\ReflectionException|Exception $e) {
            return $e->getMessage();
        }

    }

    protected static function resolveDependencies(array $parameters): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $dependencyClass = $type->getName();
                $dependencies[] = self::make($dependencyClass);
            } else {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new Exception("Nie można wstrzyknąć zależności dla parametru {$parameter->name}");
                }
            }
        }

        return $dependencies;
    }
}