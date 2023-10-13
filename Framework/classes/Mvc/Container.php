<?php

namespace Framework\Mvc;

use Framework\Mvc\Exceptions\ClassNotFound;
use Framework\Mvc\Interfaces\ContainerInterface;
use ReflectionClass;
use ReflectionException;

/**
 * DI container
 *
 * @package Framework\Mvc
 */
class Container implements ContainerInterface
{
    /** @var array<string, string> $aliases */
    protected $aliases = [];

    /** @var array<string, string> $factories */
    protected $factories = [];

    /** @var array<string> $storeInstances */
    protected $storeInstances = [];

    /** @var array<mixed> $instances */
    protected $instances = [];

    /**
     * Container constructor
     *
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        if (array_key_exists('aliases', $config)) {
            $this->aliases = $config['aliases'];
        }

        if (array_key_exists('factories', $config)) {
            $this->factories = $config['factories'];
        }

        if (array_key_exists('store_instances', $config)) {
            $this->storeInstances = $config['store_instances'];
        }
    }

    /**
     * Create instance of class
     *
     * @param string $name
     * @return object
     * @throws ClassNotFound
     */
    public function create(string $name)
    {
        $name = $this->getName($name);

        if (array_key_exists($name, $this->factories)) {
            $object = new $this->factories[$name]();
            $object = $object($this, $name);
        } else {
            $object = $this->resolve($name);
        }

        return $object;
    }

    /**
     * Store object instance in the container
     *
     * @param string $name
     * @param object $object
     */
    public function set(string $name, $object): void
    {
        $this->instances[$name] = $object;
    }

    /**
     * Get object from container
     *
     * @param string $name
     * @return object
     * @throws ClassNotFound
     */
    public function get(string $name)
    {
        $name = $this->getName($name);

        if (array_key_exists($name, $this->instances)) {
            return $this->instances[$name];
        } else {
            $object = $this->create($name);

            if (in_array($name, $this->storeInstances, true)) {
                $this->set($name, $object);
            }

            return $object;
        }
    }

    /**
     * Resolve dependencies and instantiate class
     *
     * @param string $name
     * @return mixed
     * @throws ClassNotFound
     */
    public function resolve(string $name)
    {
        try {
            $reflection = new ReflectionClass($name);
        } catch (ReflectionException $e) {
            throw new ClassNotFound("Class {$name} not found");
        }

        $constructor = $reflection->getConstructor();
        if (null === $constructor) {
            return new $name();
        }

        $parameters = $constructor->getParameters();

        $dependencies = [];
        foreach ($parameters as $parameter) {
            $type = (string)$parameter->getType();

            try {
                $dependencies[] = $this->get($type);
            } catch (ClassNotFound $e) {
                if (!$parameter->isOptional()) {
                    throw $e;
                }
            }
        }

        return new $name(...$dependencies);
    }

    /**
     * Check if container has instance stored
     *
     * @param string $name
     * @return bool
     */
    public function hasInstance(string $name): bool
    {
        return array_key_exists($name, $this->instances);
    }

    /**
     * Get class name from alias
     *
     * @param string $name
     * @return string
     */
    public function getName(string $name): string
    {
        return $this->aliases[$name] ?? $name;
    }
}

