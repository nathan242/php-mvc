<?php
namespace Soap\Soap;

use Soap\Soap\Factory\PHP2WSDLFactory;
use Soap\Soap\Factory\SoapServerFactory;
use Framework\Mvc\Exceptions\ClassNotFound;
use Framework\Mvc\Interfaces\RequestInterface;
use Framework\Mvc\Interfaces\ContainerInterface;
use SoapServer;

class Server
{
    protected $container;
    protected $PHP2WSDLFactory;
    protected $soapServerFactory;
    protected $config;
    protected $className;

    public function __construct(ContainerInterface $container, PHP2WSDLFactory $PHP2WSDLFactory, SoapServerFactory $soapServerFactory, array $config = [])
    {
        $this->container = $container;
        $this->PHP2WSDLFactory = $PHP2WSDLFactory;
        $this->soapServerFactory = $soapServerFactory;
        $this->config = $config;
    }

    public function setClass(string $class): void
    {
        $serverConfig = $this->config['server'] ?? [];
        $baseNamespace = $serverConfig['namespace'] ?? '';
        $this->className = $baseNamespace.'\\'.ucfirst($class);

        if (!class_exists($this->className)) {
            throw new ClassNotFound();
        }
    }

    public function wsdl(RequestInterface $request): string
    {
        // TODO: HTTPS flag should be added to request object
        $uri = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $uri .= $request->headers['Host'];
        $uri .= $request->path;

        $PHP2WSDL = $this->PHP2WSDLFactory->create($this->className, $uri);
        $PHP2WSDL->generateWSDL(true);
        return $PHP2WSDL->dump();
    }

    public function run(RequestInterface $request): string
    {
        $soapServer = $this->soapServerFactory->create('data://text/plaim;base64,'.base64_encode($this->wsdl($request)));
        $soapServer->setObject($this->container->get($this->className));

        ob_start();
        $soapServer->handle($request->body);
        return ob_get_clean();
    }
}

