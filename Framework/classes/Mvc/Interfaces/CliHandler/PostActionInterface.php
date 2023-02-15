<?php

namespace Framework\Mvc\Interfaces\CliHandler;

/**
 * CLI post action interface
 *
 * @package Framework\Mvc\Interfaces\CliHandler
 */
interface PostActionInterface
{
    /**
     * Process response
     *
     * @param mixed $response
     */
    public function process(&$response);
}
