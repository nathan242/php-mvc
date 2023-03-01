<?php

namespace Framework\Mvc;

use Framework\Mvc\Exceptions\ClassNotFound;
use Framework\Mvc\Exceptions\ControllerNotFound;
use Framework\Mvc\Exceptions\MethodNotFound;
use Framework\Mvc\Exceptions\PageNotFound;
use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\RequestInterface;
use Framework\Mvc\Interfaces\ResponseInterface;
use Framework\Mvc\Interfaces\RouterInterface;

/**
 * Web handler
 *
 * @package Framework\Mvc
 */
class WebHandler
{
    /** @var string $namespace */
    protected $namespace = '\\';

    /** @var ContainerInterface $container */
    protected $container;

    /** @var RouterInterface $router */
    protected $router;

    /** @var array<string> $preRoute */
    protected $preRoute = [];

    /** @var array<string> $preAction */
    protected $preAction = [];

    /** @var array<string> $postAction */
    protected $postAction = [];

    /**
     * WebHandler constructor
     *
     * @param ContainerInterface $container
     * @param RouterInterface $router
     * @param array<string, mixed> $config
     */
    public function __construct(ContainerInterface $container, RouterInterface $router, array $config = [])
    {
        $this->container = $container;
        $this->router = $router;

        if (array_key_exists('namespace', $config)) {
            $this->namespace = $config['namespace'];
        }

        if (array_key_exists('preroute', $config)) {
            $this->preRoute = $config['preroute'];
        }

        if (array_key_exists('preaction', $config)) {
            $this->preAction = $config['preaction'];
        }

        if (array_key_exists('postaction', $config)) {
            $this->postAction = $config['postaction'];
        }
    }

    /**
     * Set namespace
     *
     * @param string $namespace
     */
    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * Add pre route class
     *
     * @param string $class
     */
    public function addPreRoute(string $class)
    {
        $this->preRoute[] = $class;
    }

    /**
     * Add pre action class
     *
     * @param string $class
     */
    public function addPreAction(string $class)
    {
        $this->preAction[] = $class;
    }

    /**
     * Add post action class
     *
     * @param string $class
     */
    public function addPostAction(string $class)
    {
        $this->postAction[] = $class;
    }

    /**
     * Run pre route task
     *
     * @param RequestInterface $request
     */
    protected function runPreRoute(RequestInterface $request)
    {
        foreach ($this->preRoute as $preRoute) {
            $class = $this->container->get($preRoute);
            $class->process($request);
        }
    }

    /**
     * Run pre action task
     *
     * @param array<array<mixed>> $matchedRoute
     */
    protected function runPreAction(&$matchedRoute)
    {
        foreach ($this->preAction as $preAction) {
            $class = $this->container->get($preAction);
            $class->process($matchedRoute);
        }
    }

    /**
     * Run post action task
     *
     * @param ResponseInterface $response
     */
    protected function runPostAction(ResponseInterface $response)
    {
        foreach ($this->postAction as $postAction) {
            $class = $this->container->get($postAction);
            $class->process($response);
        }
    }

    /**
     * Process web request
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws ControllerNotFound
     * @throws MethodNotFound
     * @throws PageNotFound
     */
    public function process(RequestInterface $request)
    {
        $this->runPreRoute($request);

        $matchedRoute = $this->router->process($request);

        if (count($matchedRoute) < 2) {
            // No route
            throw new PageNotFound();
        }

        $this->runPreAction($matchedRoute);

        $response = $this->runAction($matchedRoute[0], $matchedRoute[1]);

        $this->runPostAction($response);

        return $response;
    }

    /**
     * Run web action
     *
     * @param array<int, string> $action
     * @param array<string> $params
     * @return ResponseInterface
     * @throws ControllerNotFound
     * @throws MethodNotFound
     */
    public function runAction(array $action, array $params = [])
    {
        if (!is_array($action) || count($action) < 2) {
            throw new ControllerNotFound();
        }

        $controller = strpos($action[0], '\\') === 0 ? substr($action[0], 1) : $this->namespace . '\\' . $action[0];

        try {
            $controller = $this->container->create($controller);
        } catch (ClassNotFound $e) {
            throw new ControllerNotFound($e->getMessage());
        }

        if (method_exists($controller, 'init')) {
            $controller->init();
        }

        if (!method_exists($controller, $action[1])) {
            throw new MethodNotFound();
        }

        return call_user_func_array([$controller, $action[1]], $params);
    }
}

