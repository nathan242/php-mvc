<?php

namespace Framework\Mvc\Interfaces;

/**
 * Config interface
 *
 * @package Framework\Mvc\Interfaces
 */
interface ConfigInterface
{
    /**
     * Get config
     *
     * @param string $name
     * @return array
     */
    public function get(string $name): array;
}