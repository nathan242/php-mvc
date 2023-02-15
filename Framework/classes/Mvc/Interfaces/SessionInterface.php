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
    public function start();

    /**
     * Destroy session
     */
    public function destroy();
}