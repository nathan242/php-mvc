<?php

namespace Framework\Mvc;

use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\RequestInterface;
use Framework\Mvc\Traits\SocketErrorTrait;
use Framework\Mvc\Application;
use RuntimeException;

class Server
{
    use SocketErrorTrait;

    /** @var ContainerInterface $container */
    protected $container;

    protected $socket;

    protected $preload = [];

    public function __construct(ContainerInterface $container, array $config = [])
    {
        $this->container = $container;

        if (array_key_exists('socket', $config)) {
            $this->socket = $config['socket'];
        }

        if (array_key_exists('preload', $config)) {
            $this->preload = $config['preload'];
        }
    }

    public function start(Application $application): int
    {
        if ($this->socket === null) {
            throw new RuntimeException('Socket address not configured');
        }

        $socket = socket_create(AF_UNIX, SOCK_STREAM, 0);

        if ($socket === false) {
            throw new RuntimeException('Unable to create socket. '.$this->getSocketErrorString($socket));
        }

        if (socket_bind($socket, $this->socket) === false) {
            throw new RuntimeException('Unable to bind socket. '.$this->getSocketErrorString($socket));
        }

        if (socket_listen($socket) === false) {
            throw new RuntimeException('Unable to listen on socket. '.$this->getSocketErrorString($socket));
        }

        pcntl_async_signals(true);

        pcntl_signal(SIGCHLD, function (int $signo, $siginfo) {
            $status = null;
            while (pcntl_waitpid(-1, $status, WNOHANG) > 0) {}
        });

        while (true) {
            $conn = @socket_accept($socket);

            if ($conn === false) {
                if (socket_last_error($socket) !== 0) {
                    echo 'Failed to accept connection. '.$this->getSocketErrorString($socket)."\n";
                }

                continue;
            }

            $pid = pcntl_fork();

            if ($pid === -1) {
                echo "Failed to fork child process\n";
                continue;
            } else if ($pid === 0) {
                //socket_close($socket);
                $this->handle($application, $conn);
                return 0;
            } else {
                socket_close($conn);
                $status = null;
                while (pcntl_waitpid(-1, $status, WNOHANG) > 0) {}
            }
        }

        return 0;
    }

    public function handle(Application $application, $conn)
    {
        $requestData = '';
        while ($read = socket_read($conn, 4096, PHP_NORMAL_READ)) {
            $requestData .= $read;
            if (substr($requestData, -1) === "\n") break;
        }

        $decoded = base64_decode($requestData);
        if ($decoded === false) return;

        $request = unserialize($decoded);
        if ($request === false || !$request instanceof RequestInterface) return;

        $response = $application->runWeb($request, true);

        socket_write($conn, base64_encode(serialize($response))."\n");
    }
}

