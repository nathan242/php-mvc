<?php
    namespace framework\mvc;

    use framework\mvc\exceptions\page_not_found;
    use framework\mvc\exceptions\method_not_found;
    use framework\mvc\exceptions\class_not_found;
    use framework\mvc\exceptions\controller_not_found;
    use framework\mvc\interfaces\container_interface;
    use framework\mvc\interfaces\router_interface;
    use framework\mvc\interfaces\request_interface;
    use framework\mvc\interfaces\response_interface;
    use framework\mvc\interfaces\web_handler\preroute_interface;
    use framework\mvc\interfaces\web_handler\preaction_interface;
    use framework\mvc\interfaces\web_handler\postaction_interface;

    class web_handler {
        protected $namespace = '\\';
        protected $container;
        protected $router;
        protected $preroute = [];
        protected $preaction = [];
        protected $postaction = [];

        public function __construct(container_interface $container, router_interface $router, $config = []) {
            $this->container = $container;
            $this->router = $router;

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

        public function add_preroute(preroute_interace $class) {
            $this->preroute[] = $class;
        }

        public function add_preaction(preaction_interface $class) {
            $this->preaction[] = $class;
        }

        public function add_postaction(postaction_interface $class) {
            $this->postaction[] = $class;
        }

        protected function run_preroute(request_interface $request) {
            foreach ($this->preroute as $preroute) {
                $class = $this->container->get($preroute);
                $class->process($request);
            }
        }

        protected function run_preaction(&$matched_route) {
            foreach ($this->preaction as $preaction) {
                $class = $this->container->get($preaction);
                $class->process($matched_route);
            }
        }

        protected function run_postaction(response_interface $response) {
            foreach ($this->postaction as $postaction) {
                $class = $this->container->get($postaction);
                $class->process($response);
            }
        }

        public function process(request_interface $request) {
            $this->run_preroute($request);

            $matched_route = $this->router->process($request);

            if (count($matched_route) < 2) {
                // No route
                throw new page_not_found();
            }

            $this->run_preaction($matched_route);

            $response = $this->run_action($matched_route[0], $matched_route[1]);

            $this->run_postaction($response);

            return $response;
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

