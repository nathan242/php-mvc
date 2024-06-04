<?php

namespace Framework\Mvc;

use Framework\Mvc\Exceptions\ClassNotFound;
use Framework\Mvc\Interfaces\ContainerInterface;
use ReflectionClass;
use ReflectionMethod;
use ReflectionException;
use ReflectionNamedType;

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
     * Add class alias
     *
     * @param string $alias
     * @param string $class
     */
    public function addAlias(string $alias, string $class): void
    {
        $this->aliases[$alias] = $class;
    }

    /**
     * Remove class alias
     *
     * @param string $alias
     */
    public function removeAlias(string $alias): void
    {
        unset($this->aliases[$alias]);
    }

    /**
     * Set class factory
     *
     * @param string $class
     * @param string $factory
     */
    public function setFactory(string $class, string $factory): void
    {
        $this->factories[$class] = $factory;
    }

    /**
     * Unset class factory
     *
     * @param string $class
     */
    public function unsetFactory(string $class): void
    {
        unset($this->factories[$class]);
    }

    /**
     * Set class instance to be stored
     *
     * @param string $class
     */
    public function setStoreInstance(string $class): void
    {
        if (!in_array($class, $this->storeInstances)) {
            $this->storeInstances[] = $class;
        }
    }

    /**
     * Unset class instance to be stored
     *
     * @param string $class
     */
    public function unsetStoreInstance(string $class): void
    {
        foreach ($this->storeInstances as $key => $value) {
            if ($value === $class) {
                unset($this->storeInstances[$key]);
            }
        }
    }

    /**
     * Create instance of class
     *
     * @param string $name
     * @return object
     * @throws ClassNotFound
     */
    public function create(string $name): object
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
    public function set(string $name, object $object): void
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
    public function get(string $name): object
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
     * @return object
     * @throws ClassNotFound
     */
    public function resolve(string $name): object
    {
        $constructor = $this->getConstructor($name);
        if (null === $constructor) {
            return new $name();
        }

        return new $name(...$this->getDependencies($constructor));
    }

    /**
     * Resolve dependencies with included variables and instantiate class
     *
     * @param string $name
     * @param array<mixed> $dependencies
     * @return object
     * @throws ClassNotFound
     */
    public function resolveWith(string $name, array $dependencies): object
    {
        $constructor = $this->getConstructor($name);
        if (null === $constructor) {
            return new $name(...$dependencies);
        }

        return new $name(...$this->getDependencies($constructor), ...$dependencies);
    }

    /**
     * Get class constructor reflection object
     *
     * @param string $name
     * @return null|ReflectionMethod
     * @throws ClassNotFound
     */
    protected function getConstructor(string $name): ?ReflectionMethod
    {
        try {
            $reflection = new ReflectionClass($name);
        } catch (ReflectionException $e) {
            throw new ClassNotFound("Class {$name} not found");
        }

        return $reflection->getConstructor();
    }

    /**
     * Get class dependencies
     *
     * @param ReflectionMethod $constructor
     * @return array<object>
     * @throws ClassNotFound
     */
    protected function getDependencies(ReflectionMethod $constructor): array
    {
        $parameters = $constructor->getParameters();

        $dependencies = [];
        foreach ($parameters as $parameter) {
            $type = $parameter->getType();

            try {
                if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                    $dependencies[] = $this->get((string)$type);
                }
            } catch (ClassNotFound $e) {
                if (!$parameter->isOptional()) {
                    throw $e;
                }
            }
        }

        return $dependencies;
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

