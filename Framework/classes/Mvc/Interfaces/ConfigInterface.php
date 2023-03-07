<?php

namespace Framework\Mvc\Interfaces;

/**
 * Config interface
 *
 * @package Framework\Mvc\Interfaces
 * @property array<string, mixed> $local
 */
interface ConfigInterface
{
    /**
     * Get config
     *
     * @param string $name
     * @return array<string, mixed>
     */
    public function get(string $name): array;
}
