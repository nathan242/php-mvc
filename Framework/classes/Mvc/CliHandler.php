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

    /** @var array<string> $preRoute */
    protected $preRoute = [];

    /** @var array<string> $preAction */
    protected $preAction = [];

    /** @var array<string> $postAction */
    protected $postAction = [];

    /**
     * CliHandler constructor
     *
     * @param ContainerInterface $container
     * @param CommandRouterInterface $command
     * @param array<string, mixed> $config
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
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * Add pre route class
     *
     * @param string $class
     */
    public function addPreRoute(string $class): void
    {
        $this->preRoute[] = $class;
    }

    /**
     * Add pre action class
     *
     * @param string $class
     */
    public function addPreAction(string $class): void
    {
        $this->preAction[] = $class;
    }

    /**
     * Add post action class
     *
     * @param string $class
     */
    public function addPostAction(string $class): void
    {
        $this->postAction[] = $class;
    }

    /**
     * Run pre route tasks
     *
     * @param array<string> $arguments
     */
    protected function runPreRoute(array &$arguments): void
    {
        foreach ($this->preRoute as $preRoute) {
            $class = $this->container->get($preRoute);
            $class->process($arguments);
        }
    }

    /**
     * Run pre action tasks
     *
     * @param array<int, array<mixed>> $matchedRoute
     */
    protected function runPreAction(array &$matchedRoute): void
    {
        foreach ($this->preAction as $preAction) {
            $class = $this->container->get($preAction);
            $class->process($matchedRoute);
        }
    }

    /**
     * Run post action tasks
     *
     * @param int $response
     */
    protected function runPostAction(int &$response): void
    {
        foreach ($this->postAction as $postAction) {
            $class = $this->container->get($postAction);
            $class->process($response);
        }
    }

    /**
     * Handle CLI commands
     *
     * @param array<string> $arguments
     * @return int
     * @throws CommandControllerNotFound
     * @throws CommandMethodNotFound
     * @throws CommandNotFound
     */
    public function process(array $arguments): int
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
     * @param array<int, mixed> $action
     * @param array<string> $arguments
     * @return int
     * @throws CommandControllerNotFound
     * @throws CommandMethodNotFound
     */
    public function runCommand(array $action, array $arguments): int
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

