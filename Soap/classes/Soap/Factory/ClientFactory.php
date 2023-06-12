<?php

namespace Soap\Soap\Factory;

use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\FactoryInterface;
use Soap\Soap\Factory\SoapClientFactory;

class ClientFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, string $class)
    {
        $config = $container->get('config');
        return new $class($container->get(SoapClientFactory::class), $config->get('soap'));
    }
}

