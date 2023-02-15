<?php

namespace Application\Command;

use Application\Model\Test;
use Framework\Command\BaseCommand;

class TestCommands extends BaseCommand
{
    protected $test_model;

    public function __construct(Test $test)
    {
        $this->test_model = $test;
    }

    public function dump_config($args = [])
    {
        if (isset($args[1])) {
            echo print_r($this->config->get($args[1]), 1) . "\n";
        }

        return 0;
    }

    public function show_test_records()
    {
        echo print_r($this->test_model->all()->toArray(), true) . "\n";
    }
}
