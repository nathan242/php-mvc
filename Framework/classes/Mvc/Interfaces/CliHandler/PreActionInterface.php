<?php

namespace Framework\Mvc\Interfaces\CliHandler;

/**
 * CLI pre action interface
 *
 * @package Framework\Mvc\Interfaces\CliHandler
 */
interface PreActionInterface
{
    /**
     * Process matched route
     *
     * @param array<int, array<mixed>> $matchedRoute
     */
    public function process(array &$matchedRoute): void;
}
