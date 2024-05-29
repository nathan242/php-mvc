<?php

namespace Framework\Mvc\Factory;

use Framework\Mvc\Interfaces\ConfigInterface;
use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\FactoryInterface;

/**
 * CLI handler factory
 *
 * @package Framework\Mvc\Factory
 */
class CliHandlerFactory implements FactoryInterface
{
    /**
     * Get CLI handler instance
     *
     * @param ContainerInterface $container
     * @param string $class
     * @return object
     */
    public function __invoke(ContainerInterface $container, string $class): object
    {
        $config = $container->get(ConfigInterface::class);
        return $container->resolveWith($class, [$config->get('cli_handler')]);
    }
}
