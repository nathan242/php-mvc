<?php

namespace Application\CliHandler;

use Framework\Mvc\Interfaces\CliHandler\PreRouteInterface;

class PreRoute implements PreRouteInterface
{
    public function process(array &$arguments)
    {
        echo 'PREROUTE';
        //$arguments[] = 'repl';
    }
}

