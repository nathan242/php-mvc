<?php
namespace Soap\Soap;

use Framework\Mvc\Interfaces\RequestInterface;
use Framework\Mvc\Interfaces\ResponseContentInterface;
use SoapServer;

class Runner implements ResponseContentInterface
{
    protected $soapServer;
    protected $request;

    public function set(SoapServer $soapServer, RequestInterface $request): void
    {
        $this->soapServer = $soapServer;
        $this->request = $request;
    }

    /**
     * Output response content
     */
    public function outputContent(): void
    {
        $this->soapServer->handle($this->request->body);
    }

    /**
     * Get response content as string
     *
     * @return string
     */
    public function __toString(): string
    {
        ob_start();
        $this->outputContent();
        return ob_get_clean();
    }
}
