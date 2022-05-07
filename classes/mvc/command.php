<?php
    namespace mvc;

    use mvc\exceptions\command_not_found;
    use mvc\exceptions\command_method_not_found;
    use mvc\exceptions\class_not_found;
    use mvc\exceptions\command_controller_not_found;
    use mvc\interfaces\container_interface;

    class command {
        protected $namespace = '\\';
        protected $commands = [];
        protected $default = null;
        protected $container;

        public function __construct(container_interface $container, $config = []) {
            $this->container = $container;

            if (array_key_exists('namespace', $config)) {
                $this->namespace = $config['namespace'];
            }

            if (array_key_exists('commands', $config)) {
                $this->commands = $config['commands'];
            }

            if (array_key_exists('default', $config)) {
                $this->default = $config['default'];
            }
        }

        public function set_namespace($namespace) {
            $this->namespace = $namespace;
        }

        public function command($name, $action) {
            $this->commands[$name] = $action;
        }

        public function process($arguments) {
            if (count($arguments) < 2) {
                if ($this->default !== null) {
                    $action = $this->default;
                } else {
                    return 0;
                }
            } else {
                if (!array_key_exists($arguments[1], $this->commands)) {
                    throw new command_not_found();
                }

                $action = $this->commands[$arguments[1]];
                unset($arguments[0], $arguments[1]);
            }

            return $this->run_command($action, $arguments);
        }

        public function run_command($action, $arguments) {
            if (!is_array($action) || count($action) < 2) {
                throw new command_controller_not_found();
            }

            $controller = $this->namespace.'\\'.$action[0];

            try {
                $controller = $this->container->create($controller);
            } catch (class_not_found $e) {
                throw new command_controller_not_found($e->getMessage());
            }

            if (method_exists($controller, 'init')) {
                $controller->init();
            }

            if (!method_exists($controller, $action[1])) {
                throw new command_method_not_found();
            }

            return call_user_func_array([$controller, $action[1]], $arguments);
        }
    }

