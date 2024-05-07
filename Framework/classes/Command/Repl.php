<?php

namespace Framework\Command;

use Framework\Mvc\Container;

/**
 * Read Execute Print Loop
 *
 * Framework shell launcher
 *
 * @package Framework\Command
 */
class Repl extends BaseCommand
{
    /** @var Container */
    protected $container;

    /**
     * Repl constructor
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Start framework shell
     *
     * @param array<string> $args Command arguments
     * @return int
     */
    public function shell(array $args = []): int
    {
        echo "Starting shell\n";
        require $this->config->local['root_path'] . '/../Framework/include/repl.php';

        return 0;
    }
}
