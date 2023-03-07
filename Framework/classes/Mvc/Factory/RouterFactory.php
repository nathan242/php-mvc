<?php

namespace Framework\Mvc\Factory;

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
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, string $class)
    {
        $config = $container->get('config');
        return new $class($config->get('router'));
    }
}
