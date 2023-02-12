<?php
    namespace framework\mvc;

    use framework\mvc\interfaces\router_interface;
    use framework\mvc\interfaces\request_interface;

    class router implements router_interface {
        protected $routes = [];

        public function __construct($config = []) {
            if (array_key_exists('routes', $config)) {
                $this->routes = $config['routes'];
            }
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
                return [];
            }

            return [$action, $params];
        }
    }

