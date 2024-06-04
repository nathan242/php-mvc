<?php

namespace Framework\Database\Factory;

use Framework\Mvc\Interfaces\ConfigInterface;
use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\FactoryInterface;

/**
 * Database factory
 *
 * @package Framework\Database\Factory
 */
class DbFactory implements FactoryInterface
{
    /**
     * Get configured database object
     *
     * @param ContainerInterface $container
     * @param string $class
     * @return object
     */
    public function __invoke(ContainerInterface $container, string $class): object
    {
        if ($container->hasInstance('db_driver')) {
            return $container->get('db_driver');
        }

        $config = $container->get(ConfigInterface::class);
        $dbConfig = $config->get('db');
        $db = $container->resolveWith($dbConfig['driver'], [$dbConfig]);
        $container->set('db_driver', $db);

        return $db;
    }
}

