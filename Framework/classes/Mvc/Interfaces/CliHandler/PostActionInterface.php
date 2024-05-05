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
     * @param int $response
     */
    public function process(int &$response): void;
}
