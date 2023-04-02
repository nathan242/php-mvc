<?php

namespace Application\Controller;

use Framework\Controller\BaseController;
use Framework\Mvc\Interfaces\ResponseInterface;

/**
 * Test API calls
 *
 * @package Application\Controller
 */
class Api extends BaseController
{
    /**
     * Return request body of a POST request
     *
     * @return ResponseInterface
     */
    public function postTest(): ResponseInterface
    {
        return $this->response->set(200, 'DATA: ' . $this->request->body);
    }

    /**
     * Return request method and body
     *
     * @return ResponseInterface
     */
    public function apiTest(): ResponseInterface
    {
        $ret = [
            'method' => $this->request->method,
            'body' => $this->request->body
        ];

        return $this->response->set(200, json_encode($ret, JSON_PRETTY_PRINT));
    }

    /**
     * Return request headers
     *
     * @return ResponseInterface
     */
    public function headers(): ResponseInterface
    {
        return $this->response->set(200, json_encode($this->request->headers, JSON_PRETTY_PRINT));
    }
}

