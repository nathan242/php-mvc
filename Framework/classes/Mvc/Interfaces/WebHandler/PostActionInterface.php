<?php

namespace Framework\Mvc\Interfaces\WebHandler;

use Framework\Mvc\Interfaces\ResponseInterface;

/**
 * Web post action interface
 *
 * @package Framework\Mvc\Interfaces\WebHandler
 */
interface PostActionInterface
{
    /**
     * Process response
     *
     * @param ResponseInterface $response
     */
    public function process(ResponseInterface $response);
}
