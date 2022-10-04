<?php
    namespace framework\mvc;

    use framework\mvc\exceptions\page_not_found;
    use framework\mvc\exceptions\method_not_found;
    use framework\mvc\exceptions\class_not_found;
    use framework\mvc\exceptions\controller_not_found;
    use framework\mvc\interfaces\container_interface;
    use framework\mvc\interfaces\request_interface;

    class router {
        protected $namespace = '\\';
        protected $routes = [];
        protected $container;

        public function __construct(container_interface $container, $config = []) {
            $this->container = $container;

            if (array_key_exists('namespace', $config)) {
                $this->namespace = $config['namespace'];
            }

            if (array_key_exists('routes', $config)) {
                $this->routes = $config['routes'];
            }
        }

        public function set_namespace($namespace) {
            $this->namespace = $namespace;
        }

        public function route($path, $method, $action) {
            if (!isset($this->routes[$method])) { $this->routes[$method] = []; }
            $this->routes[$method][$path] = $action;
	}

        public function process(request_interface $request) {
            foreach ([$request->method, '*'] as $method) {

                if (isset($action)) { break; }

                if (array_key_exists($method, $this->routes)) {
                    foreach ($this->routes[$method] as $path => $config) {
                        $matches = [];
                        if (preg_match('/^'.str_replace('/', '\\/', $path).'$/', $request->path, $matches)) {
                            $action = $config;
                            $params = $matches;
                            unset($params[0]);
                            break;
                        }
                    }
                }
            }

            if (!isset($action)) {
                // No route
                throw new page_not_found();
            }

            return $this->run_action($action, $params);
        }

        public function run_action($action, $params = []) {
            if (!is_array($action) || count($action) < 2) {
                throw new controller_not_found();
            }

            $controller = strpos($action[0], '\\') === 0 ? substr($action[0], 1) : $this->namespace.'\\'.$action[0];

            try {
                $controller = $this->container->create($controller);
            } catch (class_not_found $e) {
                throw new controller_not_found($e->getMessage());
            }

            if (method_exists($controller, 'init')) {
                $controller->init();
            }

            if (!method_exists($controller, $action[1])) {
                throw new method_not_found();
            }

            return call_user_func_array([$controller, $action[1]], $params);
        }
    }

