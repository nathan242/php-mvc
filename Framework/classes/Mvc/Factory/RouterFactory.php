<?php

namespace Framework\Mvc\Factory;

use Framework\Mvc\Interfaces\ConfigInterface;
use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\FactoryInterface;

/**
 * Router factory
 *
 * @package Framework\Mvc\Factory
 */
class RouterFactory implements FactoryInterface
{
    /**
     * Get router instance
     *
     * @param ContainerInterface $container
     * @param string $class
     * @return object
     */
    public function __invoke(ContainerInterface $container, string $class): object
    {
        $config = $container->get(ConfigInterface::class);
        return $container->resolveWith($class, [$config->get('router')]);
    }
}
