<?php

namespace Framework\Controller\Factory;

use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\FactoryInterface;
use Framework\Mvc\View;

/**
 * Base web controller factory for use with controllers that extend BaseController
 *
 * @package Framework\Controller\Factory
 */
class BaseFactory implements FactoryInterface
{
    /**
     * Create class
     *
     * @param ContainerInterface $container
     * @param string $class
     * @return Object
     */
    public function __invoke(ContainerInterface $container, string $class)
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
     * @param Object $classObj
     */
    protected function setObjects(ContainerInterface $container, $classObj)
    {
        $classObj->setRequest($container->get('request'));
        $classObj->setResponse($container->get('response'));
        $classObj->setSession($container->get('session'));
        $classObj->setConfig($container->get('config'));
        $classObj->setView($container->get(View::class));
    }
}
