<?php

namespace Framework\Mvc\Interfaces\WebHandler;

/**
 * Web pre action interface
 *
 * @package Framework\Mvc\Interfaces\WebHandler
 */
interface PreActionInterface
{
    /**
     * Process matched route
     *
     * @param array $matchedRoute
     */
    public function process(array &$matchedRoute);
}
