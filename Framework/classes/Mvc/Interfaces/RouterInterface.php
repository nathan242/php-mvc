<?php

namespace Framework\Mvc\Interfaces;

/**
 * Router interface
 *
 * @package Framework\Mvc\Interfaces
 */
interface RouterInterface
{
    /**
     * Add route
     *
     * @param string $path
     * @param string $method
     * @param array<string> $action
     */
    public function route(string $path, string $method, array $action);

    /**
     * Get route from request
     *
     * @param RequestInterface $request
     * @return array<array<mixed>>
     */
    public function process(RequestInterface $request): array;
}
