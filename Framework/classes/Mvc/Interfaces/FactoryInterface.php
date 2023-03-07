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
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, string $class);
}
