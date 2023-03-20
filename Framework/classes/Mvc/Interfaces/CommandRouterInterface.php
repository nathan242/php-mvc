<?php

namespace Framework\Mvc\Interfaces;

/**
 * Command router interface
 *
 * @package Framework\Mvc\Interfaces
 */
interface CommandRouterInterface
{
    /**
     * Add command route
     *
     * @param string $name
     * @param array<string> $action
     */
    public function command(string $name, array $action);

    /**
     * Get route from command arguments
     *
     * @param array<string> $arguments
     * @return array<int, array<mixed>>
     */
    public function process(array $arguments): array;
}

