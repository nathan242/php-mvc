<?php

namespace Framework\Mvc\Factory;

use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\FactoryInterface;

/**
 * Command controller factory
 *
 * @package Framework\Mvc\Factory
 */
class CommandFactory implements FactoryInterface
{
    /**
     * Get command controller instance
     *
     * @param ContainerInterface $container
     * @param string $class
     * @return object
     */
    public function __invoke(ContainerInterface $container, string $class): object
    {
        $config = $container->get('config');
        return new $class($config->get('commands'));
    }
}
