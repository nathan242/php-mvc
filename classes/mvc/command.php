<?php
    namespace mvc;

    use mvc\exceptions\command_not_found;
    use mvc\exceptions\command_method_not_found;
    use mvc\exceptions\command_controller_not_found;

    class command {
        private $namespace = '\\';
        private $commands = [];
        private $factories = [];
        private $app_config = [];

        public function __construct($config = [], $app_config = []) {
            $this->app_config = $app_config;

            if (array_key_exists('namespace', $config)) {
                $this->namespace = $config['namespace'];
            }

            if (array_key_exists('commands', $config)) {
                $this->commands = $config['commands'];
            }

            if (array_key_exists('factories', $config)) {
                $this->factories = $config['factories'];
            }
        }

        public function set_namespace($namespace) {
            $this->namespace = $namespace;
        }

        public function command($name, $action) {
            $this->commands[$name] = $action;
        }

        public function process($arguments) {
            if (count($arguments) < 2) { return $this->list_commands(); }

            if (!array_key_exists($arguments[1], $this->commands)) {
                throw new command_not_found();
            }

            $action = $this->commands[$arguments[1]];
            unset($arguments[0], $arguments[1]);

            return $this->run_command($action, $arguments);
        }

        public function run_command($action, $arguments) {
            if (!is_array($action) || count($action) < 2) {
                throw new command_controller_not_found();
            }

            $controller = $this->namespace.'\\'.$action[0];
            if (array_key_exists($action[0], $this->factories)) {
                $factory = $this->namespace.'\\'.$this->factories[$action[0]];
                $factory = new $factory();
                $controller = $factory($controller);
            } else {
                $controller = new $controller();
            }

            if (method_exists($controller, 'init')) {
                $controller->init();
            }

            if (!method_exists($controller, $action[1])) {
                throw new command_method_not_found();
            }

            return call_user_func_array([$controller, $action[1]], $arguments);
        }

        public function list_commands() {
            $app_name = array_key_exists('name', $this->app_config) ? $this->app_config['name'] : '<not configured>';
            $app_ver = array_key_exists('version', $this->app_config) ? $this->app_config['version'] : '<not configured>';

            echo "{$app_name} [{$app_ver}]\n\n";
            echo "Availiable commands:\n";

            foreach ($this->commands as $command => $details) {
                echo str_pad("{$command} ", 25);
                if (isset($details[2])) {
                    echo " - {$details[2]}";
                }
                echo "\n";
            }

            echo "\n";
        }
    }

