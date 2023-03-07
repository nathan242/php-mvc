<?php

namespace Framework\Mvc;

use Framework\Mvc\Interfaces\CommandRouterInterface;

/**
 * Command router
 *
 * @package Framework\Mvc
 */
class Command implements CommandRouterInterface
{
    /** @var array<string, array<string>> $commands */
    protected $commands = [];

    /** @var array<string>|null $default */
    protected $default = null;

    /**
     * Command constructor
     *
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        if (array_key_exists('commands', $config)) {
            $this->commands = $config['commands'];
        }

        if (array_key_exists('default', $config)) {
            $this->default = $config['default'];
        }
    }

    /**
     * Add command route
     *
     * @param string $name
     * @param array<string> $action
     */
    public function command(string $name, array $action)
    {
        $this->commands[$name] = $action;
    }

    /**
     * Get route from command arguments
     *
     * @param array<string> $arguments
     * @return array<int, array<mixed>>
     */
    public function process(array $arguments): array
    {
        array_shift($arguments);
        if (count($arguments) === 0) {
            if ($this->default !== null) {
                $action = $this->default;
            } else {
                return [];
            }
        } else {
            $command = $arguments[0];

            if (!array_key_exists($command, $this->commands)) {
                return [];
            }

            $action = $this->commands[$command];
        }

        return [$action, $arguments];
    }
}

