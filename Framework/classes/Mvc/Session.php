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
    public function __isset($name): bool
    {
        return array_key_exists($name, $_SESSION);
    }

    /**
     * Get session value
     *
     * @param mixed $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $_SESSION[$name] ?? null;
    }

    /**
     * Set session value
     *
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name, $value): void
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

