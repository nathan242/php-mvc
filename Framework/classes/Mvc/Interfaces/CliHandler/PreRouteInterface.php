<?php

namespace Framework\Mvc\Interfaces\CliHandler;

/**
 * CLI pre route interface
 *
 * @package Framework\Mvc\Interfaces\CliHandler
 */
interface PreRouteInterface
{
    /**
     * Process arguments
     *
     * @param array $arguments
     */
    public function process(array &$arguments);
}
