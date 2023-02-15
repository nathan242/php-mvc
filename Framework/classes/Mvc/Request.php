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

    /** @var string $path */
    public $path;

    /** @var array $params */
    public $params;

    /** @var string $body */
    public $body;

    /**
     * Get request data
     */
    public function get()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->path = preg_replace('/\?(.+)?/', '', $_SERVER['REQUEST_URI']);
        $this->params = [
            'GET' => $_GET,
            'POST' => $_POST,
            'FILES' => $_FILES,
            'COOKIE' => $_COOKIE
        ];
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
    public function param(string $name, $default = null, string $type = null)
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
     * @return array
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
    public function storeFile($name, string $dest): bool
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

