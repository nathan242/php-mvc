<?php

namespace Framework\Mvc\Factory;

use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\FactoryInterface;

/**
 * Web handler factory
 *
 * @package Framework\Mvc\Factory
 */
class WebHandlerFactory implements FactoryInterface
{
    /**
     * Get web handler instance
     *
     * @param ContainerInterface $container
     * @param string $class
     * @return object
     */
    public function __invoke(ContainerInterface $container, string $class): object
    {
        $config = $container->get('config');
        return new $class($container, $container->get('router'), $config->get('web_handler'));
    }
}
