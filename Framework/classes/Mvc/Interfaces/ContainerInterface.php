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
     * Add class alias
     *
     * @param string $alias
     * @param string $class
     */
    public function addAlias(string $alias, string $class): void;

    /**
     * Remove class alias
     *
     * @param string $alias
     */
    public function removeAlias(string $alias): void;

    /**
     * Set class factory
     *
     * @param string $class
     * @param string $factory
     */
    public function setFactory(string $class, string $factory): void;

    /**
     * Unset class factory
     *
     * @param string $class
     */
    public function unsetFactory(string $class): void;

    /**
     * Set class instance to be stored
     *
     * @param string $class
     */
    public function setStoreInstance(string $class): void;

    /**
     * Unset class instance to be stored
     *
     * @param string $class
     */
    public function unsetStoreInstance(string $class): void;

    /**
     * Create instance of class
     *
     * @param string $name
     * @return object
     */
    public function create(string $name): object;

    /**
     * Resolve dependencies and instantiate class
     *
     * @param string $name
     * @return object
     */
    public function resolve(string $name): object;

    /**
     * Store object instance in the container
     *
     * @param string $name
     * @param object $object
     */
    public function set(string $name, object $object): void;

    /**
     * Get object from container
     *
     * @param string $name
     * @return object
     */
    public function get(string $name): object;

    /**
     * Check if container has instance stored
     *
     * @param string $name
     * @return bool
     */
    public function hasInstance(string $name): bool;
}
