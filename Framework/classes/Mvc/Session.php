<?php

namespace Framework\Mvc;

use Framework\Mvc\Interfaces\SessionInterface;

/**
 * Session handling
 *
 * @package Framework\Mvc
 */
class Session implements SessionInterface
{
    /**
     * Start session
     */
    public function start(): void
    {
        session_start();
    }

    /**
     * Check if session key exists
     *
     * @param mixed $name
     * @return bool
     */
    public function __isset(mixed $name): bool
    {
        return array_key_exists($name, $_SESSION);
    }

    /**
     * Get session value
     *
     * @param mixed $name
     * @return mixed
     */
    public function __get(mixed $name): mixed
    {
        return $_SESSION[$name] ?? null;
    }

    /**
     * Set session value
     *
     * @param mixed $name
     * @param mixed $value
     */
    public function __set(mixed $name, mixed $value): void
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Destroy session
     */
    public function destroy(): void
    {
        session_destroy();
    }
}

