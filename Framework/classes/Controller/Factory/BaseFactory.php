<?php

namespace Framework\Controller\Factory;

use Framework\Mvc\Interfaces\ConfigInterface;
use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\FactoryInterface;
use Framework\Mvc\Interfaces\RequestInterface;
use Framework\Mvc\Interfaces\ResponseInterface;
use Framework\Mvc\Interfaces\SessionInterface;
use Framework\Mvc\Interfaces\ViewInterface;

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
        $classObj->setRequest($container->get(RequestInterface::class));
        $classObj->setResponse($container->get(ResponseInterface::class));
        $classObj->setSession($container->get(SessionInterface::class));
        $classObj->setConfig($container->get(ConfigInterface::class));
        $classObj->setView($container->get(ViewInterface::class));
    }
}
