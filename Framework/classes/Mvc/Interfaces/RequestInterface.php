<?php

namespace Framework\Mvc\Interfaces;

/**
 * Request interface
 *
 * @package Framework\Mvc\Interfaces
 */
interface RequestInterface
{
    /**
     * Get request data
     */
    public function get();

    /**
     * Get request parameter
     *
     * @param string $name
     * @param mixed $default
     * @param string|null $type
     * @return mixed
     */
    public function param(string $name, $default =  null, string $type = null);

    /**
     * Check if request parameter exists
     *
     * @param string $name
     * @param string $type
     * @return bool
     */
    public function hasParam(string $name, string $type = null): bool;

    /**
     * Get information about files sent in request
     *
     * @return array
     */
    public function files(): array;

    /**
     * Store file sent in request
     *
     * @param string|null $name
     * @param string $dest
     * @return bool
     */
    public function storeFile($name, string $dest): bool;
}
