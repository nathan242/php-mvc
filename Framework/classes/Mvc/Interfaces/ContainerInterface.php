<?php

namespace Framework\Mvc\Interfaces;

/**
 * Container interface
 *
 * @package Framework\Mvc\Interfaces
 */
interface ContainerInterface
{
    /**
     * Create instance of class
     *
     * @param string $name
     * @return object
     */
    public function create(string $name);

    /**
     * Resolve dependencies and instantiate class
     *
     * @param string $name
     * @return mixed
     */
    public function resolve(string $name);

    /**
     * Store object instance in the container
     *
     * @param string $name
     * @param object $object
     */
    public function set(string $name, $object): void;

    /**
     * Get object from container
     *
     * @param string $name
     * @return object
     */
    public function get(string $name);

    /**
     * Check if container has instance stored
     *
     * @param string $name
     * @return bool
     */
    public function hasInstance(string $name): bool;
}
