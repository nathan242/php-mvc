<?php

namespace Framework\Mvc\Interfaces;

/**
 * Session interface
 *
 * @package Framework\Mvc\Interfaces
 */
interface SessionInterface
{
    /**
     * Start session
     */
    public function start(): void;

    /**
     * Destroy session
     */
    public function destroy(): void;

    /**
     * Check if session key exists
     *
     * @param mixed $name
     * @return bool
     */
    public function __isset($name): bool;

    /**
     * Get session value
     *
     * @param mixed $name
     * @return mixed|null
     */
    public function __get($name);

    /**
     * Set session value
     *
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name, $value): void;
}
