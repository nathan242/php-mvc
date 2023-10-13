<?php

namespace Application\Controller\Factory;

use Application\Model\User;
use Framework\Controller\Factory\BaseFactory;
use Framework\Mvc\Interfaces\ContainerInterface;

/**
 * Base web controller factory
 *
 * @package Application\Controller\Factory
 */
class BaseAppFactory extends BaseFactory
{
    /**
     * Set the user object
     *
     * @param ContainerInterface $container
     * @param Object $class
     */
    protected function setObjects(ContainerInterface $container, $class): void
    {
        $class->setUser($container->get(User::class));
        parent::setObjects($container, $class);
    }
}

