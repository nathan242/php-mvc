<?php

namespace Application\CliHandler;

use Framework\Mvc\Interfaces\CliHandler\PostActionInterface;

/**
 * Test post action
 *
 * @package Application\CliHandler
 */
class PostAction implements PostActionInterface
{
    /**
     * @param mixed $response
     */
    public function process(&$response)
    {
        echo 'POSTACTION: ' . print_r($response, 1);
        //$response = 255;
    }
}

