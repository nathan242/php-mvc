<?php
    namespace framework\mvc;

    use framework\mvc\exceptions\command_not_found;
    use framework\mvc\exceptions\command_method_not_found;
    use framework\mvc\exceptions\class_not_found;
    use framework\mvc\exceptions\command_controller_not_found;
    use framework\mvc\interfaces\container_interface;
    use framework\mvc\interfaces\command_router_interface;

    class cli_handler {
        protected $namespace = '\\';
        protected $container;
        protected $command;
        protected $preroute = [];
        protected $preaction = [];
        protected $postaction = [];

        public function __construct(container_interface $container, command_router_interface $command, $config = []) {
            $this->container = $container;
            $this->command = $command;

            if (array_key_exists('namespace', $config)) {
                $this->namespace = $config['namespace'];
            }

            if (array_key_exists('preroute', $config)) {
                $this->preroute = $config['preroute'];
            }

            if (array_key_exists('preaction', $config)) {
                $this->preaction = $config['preaction'];
            }

            if (array_key_exists('postaction', $config)) {
                $this->postaction = $config['postaction'];
            }
        }

        public function set_namespace($namespace) {
            $this->namespace = $namespace;
        }

        public function add_preroute($class) {
            $this->preroute[] = $class;
        }

        public function add_preaction($class) {
            $this->preaction[] = $class;
        }

        public function add_postaction($class) {
            $this->postaction[] = $class;
        }

        protected function run_preroute(&$arguments) {
            foreach ($this->preroute as $preroute) {
                $class = $this->container->get($preroute);
                $class->process($arguments);
            }
        }

        protected function run_preaction(&$matched_route) {
            foreach ($this->preaction as $preaction) {
                $class = $this->container->get($preaction);
                $class->process($matched_route);
            }
        }

        protected function run_postaction(&$response) {
            foreach ($this->postaction as $postaction) {
                $class = $this->container->get($postaction);
                $class->process($response);
            }
        }

        public function process($arguments) {
            $this->run_preroute($arguments);

            $matched_route = $this->command->process($arguments);

            if (count($matched_route) < 2) {
                if (count($arguments) < 2) {
                    return 0;
                }

                throw new command_not_found();
            }

            $this->run_preaction($matched_route);

            $return = $this->run_command($matched_route[0], $matched_route[1]);

            $this->run_postaction($return);

            return $return;
        }

        public function run_command($action, $arguments) {
            if (!is_array($action) || count($action) < 2) {
                throw new command_controller_not_found();
            }

            $controller = strpos($action[0], '\\') === 0 ? substr($action[0], 1) : $this->namespace.'\\'.$action[0];

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

            return $controller->{$action[1]}($arguments);
        }
    }

