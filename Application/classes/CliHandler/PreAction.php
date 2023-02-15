<?php

namespace Application\CliHandler;

use Framework\Mvc\Interfaces\CliHandler\PreActionInterface;

class PreAction implements PreActionInterface
{
    public function process(&$matched_route)
    {
        echo 'PREACTION: ' . print_r($matched_route, 1);
        //$matched_route[0] = 'repl';
    }

}

