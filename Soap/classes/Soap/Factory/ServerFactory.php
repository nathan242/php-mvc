<?php

namespace Soap\Soap\Factory;

use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\FactoryInterface;
use Soap\Soap\Factory\PHP2WSDLFactory;
use Soap\Soap\Factory\SoapServerFactory;
use Soap\Soap\Runner;

class ServerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, string $class): object
    {
        $config = $container->get('config');
        return new $class(
            $container,
            $container->get(PHP2WSDLFactory::class),
            $container->get(SoapServerFactory::class),
            $container->get(Runner::class),
            $config->get('soap')
        );
    }
}

