<?php

namespace Application\WebHandler;

use Framework\Mvc\Interfaces\ResponseInterface;
use Framework\Mvc\Interfaces\WebHandler\PostActionInterface;

class PostAction implements PostActionInterface
{
    public function process(ResponseInterface $response)
    {
        echo '<pre>' . print_r($response, 1) . '</pre>';
    }
}

