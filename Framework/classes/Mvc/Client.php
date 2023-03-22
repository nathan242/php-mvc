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

        if (socket_write($socket, base64_encode(serialize($request))."\n") === false) {
            throw new RuntimeException('Unable to write to socket. '.$this->getSocketErrorString($socket));
        }

        $responseData = '';
        while ($read = socket_read($socket, 4096, PHP_NORMAL_READ)) {
            $responseData .= $read;
            if (substr($responseData, -1) === "\n") break;
        }

        $decoded = base64_decode($responseData);
        if ($decoded === false) {
            throw new RuntimeException('Unable to decode response');
        }

        $response = unserialize($decoded);
        if ($response === false || !$response instanceof ResponseInterface) {
            throw new RuntimeException('Invalid response');
        }

        return $response;
    }
}

