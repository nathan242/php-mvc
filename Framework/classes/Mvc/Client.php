<?php

namespace Framework\Mvc;

use Framework\Mvc\Interfaces\RequestInterface;
use Framework\Mvc\Interfaces\ResponseInterface;
use Framework\Mvc\Traits\SocketErrorTrait;
use RuntimeException;

class Client
{
    use SocketErrorTrait;

    protected $socket;

    public function __construct(array $config = [])
    {
        if (array_key_exists('socket', $config)) {
            $this->socket = $config['socket'];
        }
    }

    public function process(RequestInterface $request): ResponseInterface
    {
        if ($this->socket === null) {
            throw new RuntimeException('Socket address not configured');
        }

        $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);

        if (socket_connect($socket, $this->socket) === false) {
            throw new RuntimeException('Unable to connect to socket. '.$this->getSocketErrorString($socket));
        }

        $requestData = serialize($request);
        $requestSize = strlen($requestData);

        if (socket_write($socket, "{$requestSize}\n{$requestData}") === false) {
            throw new RuntimeException('Unable to write to socket. '.$this->getSocketErrorString($socket));
        }

        $size = (int)socket_read($socket, 4096, PHP_NORMAL_READ);
        $responseData = socket_read($socket, $size);

        $response = unserialize($responseData);
        if ($response === false || !$response instanceof ResponseInterface) {
            throw new RuntimeException('Invalid response');
        }

        return $response;
    }
}

