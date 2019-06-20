<?php
    namespace mvc;

    use mvc\exceptions\page_not_found;
    use mvc\exceptions\method_not_found;

    class router {
        private $routes = [];
        private $namespace = '\\';

        public function set_namespace($namespace) {
            $this->namespace = $namespace;
        }

        public function route($path, $method, $action) {
            if (!isset($this->routes[$method])) { $this->routes[$method] = []; }
            $this->routes[$method][$path] = $action;
	}

        public function process() {
            $request_method = $_SERVER['REQUEST_METHOD'];
            $request_path = $_SERVER['PATH_INFO'];

            foreach (array_keys($this->routes[$request_method]) as $path) {
                $matches = [];
                if (preg_match('/^'.str_replace('/', '\\/', $path).'$/', $request_path, $matches)) {
                    $action = $this->routes[$request_method][$path];
                    $params = $matches;
                    unset($params[0]);
                }
            }

            if (isset($action)) {
                return $this->run_action($action, $params);
            } else {
                // No route
                throw new page_not_found();
            }
        }

        public function run_action($action, $params = []) {
            if (!is_array($action) || count($action) < 2) {
                throw new controller_not_found();
            }

            $controller = $action[0];
            if (strpos($controller, '\\') === false) {
                $controller = $this->namespace.'\\'.$controller;
            }

            $controller = new $controller();
            if (!method_exists($controller, $action[1])) {
                throw new method_not_found();
            }

            return call_user_func_array([$controller, $action[1]], $params);
        }
    }

