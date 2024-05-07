<?php

namespace Framework\Mvc\Interfaces;

/**
 * Factory interface
 *
 * @package Framework\Mvc\Interfaces
 */
interface FactoryInterface
{
    /**
     * Get object instance
     *
     * @param ContainerInterface $container
     * @param string $class
     * @return object
     */
    public function __invoke(ContainerInterface $container, string $class): object;
}
