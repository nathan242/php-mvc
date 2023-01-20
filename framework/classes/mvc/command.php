<?php
    namespace framework\mvc;

    use framework\mvc\interfaces\command_router_interface;

    class command implements command_router_interface {
        protected $commands = [];
        protected $default = null;

        public function __construct($config = []) {
            if (array_key_exists('commands', $config)) {
                $this->commands = $config['commands'];
            }

            if (array_key_exists('default', $config)) {
                $this->default = $config['default'];
            }
        }

        public function command($name, $action) {
            $this->commands[$name] = $action;
        }

        public function process($arguments) {
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

