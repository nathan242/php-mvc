<?php

namespace Framework\Mvc;

use Framework\Mvc\Interfaces\RequestInterface;

/**
 * Web request
 *
 * @package Framework\Mvc
 */
class Request implements RequestInterface
{
    /** @var string $method */
    public $method;

    /** @var string $remoteAddr */
    public $remoteAddr;

    /** @var string $remotePort */
    public $remotePort;

    /** @var int $requestTime */
    public $requestTime;

    /** @var string $protocol */
    public $protocol;

    /** @var string $path */
    public $path;

    /** @var array<string, array<mixed>> $params */
    public $params;

    /** @var array<string, string> $headers */
    public $headers;

    /** @var string $body */
    public $body;

    /**
     * Get request data
     */
    public function get(): void
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->remoteAddr = $_SERVER['REMOTE_ADDR'];
        $this->remotePort = $_SERVER['REMOTE_PORT'];
        $this->requestTime = $_SERVER['REQUEST_TIME'];
        $this->protocol = (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $this->path = preg_replace('/\?(.+)?/', '', $_SERVER['REQUEST_URI']);
        $this->params = [
            'GET' => $_GET,
            'POST' => $_POST,
            'FILES' => $_FILES,
            'COOKIE' => $_COOKIE
        ];

        $this->headers = [];
        foreach($_SERVER as $key => $value) {
            if (substr($key, 0, 5) === 'HTTP_') {
                $header = ucwords(strtolower(str_replace('_', '-', substr($key, 5))), '-');
                $this->headers[$header] = $value;
            }
        }

        $this->body = file_get_contents('php://input');
    }

    /**
     * Get request parameter
     *
     * @param string $name
     * @param mixed $default
     * @param string|null $type
     * @return mixed
     */
    public function param(string $name, mixed $default = null, string $type = null): mixed
    {
        $return = $default;
        $type = null === $type ? ['GET', 'POST'] : (array)$type;

        foreach ($type as $paramType) {
            if (array_key_exists($name, $this->params[$paramType])) {
                $return = $this->params[$paramType][$name];
                break;
            }
        }

        return $return;
    }

    /**
     * Get all request parameters
     *
     * @param array<string> $order
     * @return array<string, mixed>
     */
    public function allParams(array $order = ['GET', 'POST']): array
    {
        $return = [];

        while (($next = array_shift($order)) !== null) {
            $return = array_merge($return, $this->params[$next]);
        }

        return $return;
    }

    /**
     * Check if request parameter exists
     *
     * @param string $name
     * @param string $type
     * @return bool
     */
    public function hasParam(string $name, string $type = null): bool
    {
        return null !== $this->param($name, null, $type);
    }

    /**
     * Get information about files sent in request
     *
     * @return array<string>
     */
    public function files(): array
    {
        $files = [];

        foreach ($this->params['FILES'] as $file) {
            $files[] = $file['name'];
        }

        return $files;
    }

    /**
     * Store file sent in request
     *
     * @param string|null $name
     * @param string $dest
     * @return bool
     */
    public function storeFile(string|null $name, string $dest): bool
    {
        $fileData = null;

        foreach ($this->params['FILES'] as $file) {
            if ($name === null || $file['name'] === $name) {
                $fileData = $file;

                break;
            }
        }

        if ($fileData === null) {
            return false;
        }

        return move_uploaded_file($fileData['tmp_name'], $dest);
    }
}

