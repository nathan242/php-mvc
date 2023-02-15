<?php

namespace Application\Controller\Factory;

use Application\Model\User;
use Framework\Controller\Factory\BaseFactory;
use Framework\Mvc\Interfaces\ContainerInterface;

class BaseAppFactory extends BaseFactory
{
    protected function setObjects(ContainerInterface $container, $class)
    {
        $class->setUser($container->get(User::class));
        parent::setObjects($container, $class);
    }
}

