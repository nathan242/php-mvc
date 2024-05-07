<?php

namespace Framework\Mvc;

use Framework\Mvc\Interfaces\RequestInterface;
use Framework\Mvc\Interfaces\RouterInterface;

/**
 * Web router
 *
 * @package Framework\Mvc
 */
class Router implements RouterInterface
{
    /** @var array<string, array<string, array<string, array<string>>>> $routes */
    protected $routes = [];

    /**
     * Router constructor
     *
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        if (array_key_exists('routes', $config)) {
            $this->routes = $config['routes'];
        }
    }

    /**
     * Add route
     *
     * @param string $path
     * @param string $method
     * @param array<string> $action
     */
    public function route(string $path, string $method, array $action): void
    {
        if (!isset($this->routes[$method])) {
            $this->routes[$method] = [];
        }
        $this->routes[$method][$path] = $action;
    }

    /**
     * Get route from request
     *
     * @param RequestInterface $request
     * @return array<array<mixed>>
     */
    public function process(RequestInterface $request): array
    {
        $action = null;
        $params = null;

        foreach ([$request->method, '*'] as $method) {
            if ($action !== null) {
                break;
            }

            if (array_key_exists($method, $this->routes)) {
                foreach ($this->routes[$method] as $path => $config) {
                    $matches = [];
                    if (preg_match('/^' . str_replace('/', '\\/', $path) . '$/', $request->path, $matches)) {
                        $action = $config;
                        $params = $matches;
                        unset($params[0]);
                        break;
                    }
                }
            }
        }

        if ($action === null) {
            // No route
            return [];
        }

        return [$action, $params];
    }
}

