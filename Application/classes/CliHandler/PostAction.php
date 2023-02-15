<?php

namespace Application\CliHandler;

use Framework\Mvc\Interfaces\CliHandler\PostActionInterface;

class PostAction implements PostActionInterface
{
    public function process(&$response)
    {
        echo 'POSTACTION: ' . print_r($response, 1);
        //$response = 255;
    }
}

