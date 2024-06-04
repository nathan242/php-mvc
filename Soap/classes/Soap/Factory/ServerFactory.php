<?php

namespace Soap\Soap\Factory;

use Framework\Mvc\Interfaces\ConfigInterface;
use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\FactoryInterface;

class ServerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, string $class): object
    {
        $config = $container->get(ConfigInterface::class);
        return $container->resolveWith($class, [$config->get('soap')]);
    }
}

