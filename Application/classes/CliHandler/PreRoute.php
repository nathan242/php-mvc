<?php

namespace Application\CliHandler;

use Framework\Mvc\Interfaces\CliHandler\PreRouteInterface;

/**
 * Test pre route
 *
 * @package Application\CliHandler
 */
class PreRoute implements PreRouteInterface
{
    /**
     * @param array<string> $arguments
     */
    public function process(array &$arguments)
    {
        echo 'PREROUTE';
        //$arguments[] = 'repl';
    }
}

