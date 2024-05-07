<?php

namespace Application\WebHandler;

use Framework\Mvc\Interfaces\ResponseInterface;
use Framework\Mvc\Interfaces\WebHandler\PostActionInterface;

/**
 * Test post action
 *
 * @package Application\WebHandler
 */
class PostAction implements PostActionInterface
{
    /**
     * @param ResponseInterface $response
     */
    public function process(ResponseInterface $response): void
    {
        echo '<pre>' . print_r($response, true) . '</pre>';
    }
}

