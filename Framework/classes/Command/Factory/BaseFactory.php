<?php

namespace Framework\Command\Factory;

use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\FactoryInterface;

/**
 * Base command factory for use with commands that extend BaseCommand
 *
 * @package Framework\Command\Factory
 */
class BaseFactory implements FactoryInterface
{
    /**
     * Create class
     *
     * @param ContainerInterface $container
     * @param string $class
     * @return object
     */
    public function __invoke(ContainerInterface $container, string $class): object
    {
        if (method_exists($this, 'create')) {
            $classObj = $this->create($container, $class);
        } else {
            $classObj = $container->resolve($class);
        }

        $this->setObjects($container, $classObj);

        return $classObj;
    }

    /**
     * Inject objects into the class
     *
     * @param ContainerInterface $container
     * @param object $classObj
     */
    protected function setObjects(ContainerInterface $container, object $classObj): void
    {
        $classObj->setConfig($container->get('config'));
    }
}
