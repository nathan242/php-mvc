<?php

namespace Framework\Mvc\Exceptions;

use Framework\Mvc\Interfaces\ResponseInterface;
use Exception;

/**
 * Exception with response object
 *
 * @package Framework\Mvc\Exceptions
 */
class ResponseException extends Exception
{
    /** @var ResponseInterface $response */
    protected $response;

    /**
     * Create exception with response object
     *
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        parent::__construct('Unhandled response exception');
    }

    /**
     * Get response
     *
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
