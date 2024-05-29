<?php

namespace Framework\Command;

use Framework\Mvc\Interfaces\ContainerInterface;

/**
 * Read Execute Print Loop
 *
 * Framework shell launcher
 *
 * @package Framework\Command
 */
class Repl extends BaseCommand
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * Repl constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
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
