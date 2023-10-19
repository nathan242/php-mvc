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
     * @param int $response
     */
    public function process(&$response): void
    {
        echo 'POSTACTION: ' . print_r($response, true);
        //$response = 255;
    }
}

