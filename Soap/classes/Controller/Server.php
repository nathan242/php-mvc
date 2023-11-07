<?php

namespace Soap\Controller;

use Framework\Controller\BaseController;
use Framework\Mvc\Interfaces\ResponseInterface;
use Framework\Mvc\Exceptions\ClassNotFound;
use Soap\Soap\Server as ServerObj;

class Server extends BaseController
{
    protected $server;

    public function __construct(ServerObj $server)
    {
        $this->server = $server;
    }

    public function wsdl(string $path): ResponseInterface
    {
        try {
            $this->server->setClass($path);
        } catch (ClassNotFound $e) {
            return $this->response->set(404, 'Service not found');
        }

        return $this->response->set(200, $this->server->wsdl($this->request), ['Content-Type' => 'text/xml']);
    }

    public function server(string $path): ResponseInterface
    {
        try {
            $this->server->setClass($path);
        } catch (ClassNotFound $e) {
            return $this->response->set(404, 'Service not found');
        }

        return $this->response->set(200, $this->server->run($this->request), ['Content-Type' => 'text/xml']);
    }
}

