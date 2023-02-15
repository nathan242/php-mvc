<?php

namespace Framework\Mvc;

use Framework\Mvc\Exceptions\ClassNotFound;
use Framework\Mvc\Exceptions\CommandControllerNotFound;
use Framework\Mvc\Exceptions\CommandMethodNotFound;
use Framework\Mvc\Exceptions\CommandNotFound;
use Framework\Mvc\Interfaces\CommandRouterInterface;
use Framework\Mvc\Interfaces\ContainerInterface;

/**
 * CLI handling
 *
 * @package Framework\Mvc
 */
class CliHandler
{
    /** @var string $namespace */
    protected $namespace = '\\';

    /** @var ContainerInterface $container */
    protected $container;

    /** @var CommandRouterInterface $command */
    protected $command;

    /** @var array $preroute */
    protected $preRoute = [];

    /** @var array $preaction */
    protected $preAction = [];

    /** @var array $postaction */
    protected $postAction = [];

    /**
     * CliHandler constructor
     *
     * @param ContainerInterface $container
     * @param CommandRouterInterface $command
     * @param array $config
     */
    public function __construct(ContainerInterface $container, CommandRouterInterface $command, array $config = [])
    {
        $this->container = $container;
        $this->command = $command;

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
     * Run pre route tasks
     *
     * @param array $arguments
     */
    protected function runPreRoute(array &$arguments)
    {
        foreach ($this->preRoute as $preRoute) {
            $class = $this->container->get($preRoute);
            $class->process($arguments);
        }
    }

    /**
     * Run pre action tasks
     *
     * @param array $matchedRoute
     */
    protected function runPreAction(array &$matchedRoute)
    {
        foreach ($this->preAction as $preAction) {
            $class = $this->container->get($preAction);
            $class->process($matchedRoute);
        }
    }

    /**
     * Run post action tasks
     *
     * @param mixed $response
     */
    protected function runPostAction(&$response)
    {
        foreach ($this->postAction as $postAction) {
            $class = $this->container->get($postAction);
            $class->process($response);
        }
    }

    /**
     * Handle CLI commands
     *
     * @param array $arguments
     * @return int
     * @throws CommandControllerNotFound
     * @throws CommandMethodNotFound
     * @throws CommandNotFound
     */
    public function process(array $arguments)
    {
        $this->runPreRoute($arguments);

        $matchedRoute = $this->command->process($arguments);

        if (count($matchedRoute) < 2) {
            if (count($arguments) < 2) {
                return 0;
            }

            throw new CommandNotFound();
        }

        $this->runPreAction($matchedRoute);

        $return = $this->runCommand($matchedRoute[0], $matchedRoute[1]);

        $this->runPostAction($return);

        return $return;
    }

    /**
     * Run CLI command
     *
     * @param array $action
     * @param array $arguments
     * @return mixed
     * @throws CommandControllerNotFound
     * @throws CommandMethodNotFound
     */
    public function runCommand(array $action, array $arguments)
    {
        if (!is_array($action) || count($action) < 2) {
            throw new CommandControllerNotFound();
        }

        $controller = strpos($action[0], '\\') === 0 ? substr($action[0], 1) : $this->namespace . '\\' . $action[0];

        try {
            $controller = $this->container->create($controller);
        } catch (ClassNotFound $e) {
            throw new CommandControllerNotFound($e->getMessage());
        }

        if (method_exists($controller, 'init')) {
            $controller->init();
        }

        if (!method_exists($controller, $action[1])) {
            throw new CommandMethodNotFound();
        }

        return $controller->{$action[1]}($arguments);
    }
}

