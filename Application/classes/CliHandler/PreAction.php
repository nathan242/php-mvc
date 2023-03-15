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
     * @param array<int, array<mixed>> $matchedRoute
     */
    public function process(array &$matchedRoute)
    {
        echo 'PREACTION: ' . print_r($matchedRoute, true);
        //$matchedRoute[0] = 'repl';
    }

}

