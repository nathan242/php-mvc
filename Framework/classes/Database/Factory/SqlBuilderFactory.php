<?php

namespace Framework\Database\Factory;

use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\FactoryInterface;

/**
 * SQL builder factory
 *
 * @package Framework\Database\Factory
 */
class SqlBuilderFactory implements FactoryInterface
{
    /**
     * Get SQL builder for configured DB
     *
     * @param ContainerInterface $container
     * @param string $class
     * @return object
     */
    public function __invoke(ContainerInterface $container, string $class): object
    {
        $config = $container->get('config');
        $dbConfig = $config->get('db');

        return $container->get($dbConfig['sql_builder']);
    }
}

