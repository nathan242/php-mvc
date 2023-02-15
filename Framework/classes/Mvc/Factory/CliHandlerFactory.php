<?php

namespace Framework\Mvc\Factory;

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
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, string $class)
    {
        $config = $container->get('config');
        return new $class($container, $container->get('command'), $config->get('cli_handler'));
    }
}
