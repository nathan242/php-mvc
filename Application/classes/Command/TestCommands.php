<?php

namespace Application\Command;

use Application\Model\Test;
use Framework\Command\BaseCommand;

/**
 * Test commands
 *
 * @package Application\Command
 */
class TestCommands extends BaseCommand
{
    /** @var Test $testModel */
    protected $testModel;

    /**
     * TestCommands constructor
     *
     * @param Test $test
     */
    public function __construct(Test $test)
    {
        $this->testModel = $test;
    }

    /**
     * Dump config data for specified key
     *
     * @param array $args
     * @return int
     */
    public function dumpConfig(array $args = []): int
    {
        if (isset($args[1])) {
            echo print_r($this->config->get($args[1]), 1) . "\n";
        }

        return 0;
    }

    /**
     * Dump records in the test table
     *
     * @param array $args
     * @return int
     */
    public function showTestRecords(array $args = []): int
    {
        echo print_r($this->testModel->all()->toArray(), true) . "\n";

        return 0;
    }
}
