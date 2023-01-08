<?php

namespace App\Core;

use Exception;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $bindings = [];
    private array $singles = [];
    private array $bound = [];

    /**
     * Retrieve the requested class, resolve if necessary
     */
    public function get(string $id)
    {
        if ($this->has($id)) {
            if (array_key_exists($id, $this->singles)) {
                if (array_key_exists($id, $this->bound)) {
                    return $this->bound[$id];
                }
                $bound = $this->singles[$id]($this);
                $this->bound[$id] = $bound;

                return $bound;
            }

            return $this->bindings[$id]($this);
        }

        return $this->resolve($id);
    }

    /**
     * Set a class and it's binding into the container
     */
    public function set(string $id, callable $callback)
    {
        $this->bindings[$id] = $callback;
    }

    /**
     * Set a class and it's binding into the container just once
     * Use the instantiated class on all subsequent references in the container
     */
    public function setOnce(string $id, callable $callback)
    {
        $this->singles[$id] = $callback;
    }

    /**
     * Check if we have the class registered
     */
    public function has(string $id): bool
    {
        return (isset($this->bindings[$id]) || isset($this->singles[$id]));
    }

    /**
     * Using Reflection to create the requested class
     */
    public function resolve(string $id)
    {
        $reflectionClass = new ReflectionClass($id);
        if (!$reflectionClass->isInstantiable()) {
            throw new Exception('The provided class: "' . $id . '" is not instantiable.');
        }

        $constructor = $reflectionClass->getConstructor();
        if (!$constructor) {
            return new $id();
        }

        $parameters = $constructor->getParameters();
        if (!$parameters) {
            return new $id();
        }

        // gather dependencies
        $dependencies = array_map(
            function (ReflectionParameter $parameter) use ($id) {
                $name = $parameter->getName();
                $type = $parameter->getType();

                if (!$type) {
                    throw new Exception(
                        'Failed to resolve class: "' . $id . '" because "' . $name . '" ' .
                        'is missing a type hint in the constructor.'
                    );
                }

                if ($type instanceof ReflectionUnionType) {
                    throw new Exception(
                        'Failed to resolve class: "' . $id . '" because param: "' . $name . '" ' .
                        'is a union type.'
                    );
                }

                if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                    return $this->get($type->getName());
                }

                if ($type->isBuiltin() && $parameter->isDefaultValueAvailable()) {
                    return $parameter->getDefaultValue();
                }

                throw new Exception(
                    'Failed to resolve class: "' . $id . '" because invalid param: "' . $name . '". ' .
                    'If you are using a built-in type, please provide a default value.'
                );
            },
            $parameters
        );

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}
