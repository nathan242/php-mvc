<?php

namespace Application\CliHandler;

use Framework\Mvc\Interfaces\CliHandler\PreActionInterface;

/**
 * Test pre action
 *
 * @package Application\CliHandler
 */
class PreAction implements PreActionInterface
{
    /**
     * @param array $matchedRoute
     */
    public function process(array &$matchedRoute)
    {
        echo 'PREACTION: ' . print_r($matchedRoute, 1);
        //$matchedRoute[0] = 'repl';
    }

}

