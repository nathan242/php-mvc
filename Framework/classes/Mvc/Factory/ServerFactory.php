<?php

namespace Framework\Mvc\Factory;

use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\FactoryInterface;

/**
 * Server factory
 *
 * @package Framework\Mvc\Factory
 */
class ServerFactory implements FactoryInterface
{
    /**
     * Get server instance
     *
     * @param ContainerInterface $container
     * @param string $class
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, string $class)
    {
        $config = $container->get('config');
        return new $class($container, $config->get('server'));
    }
}
