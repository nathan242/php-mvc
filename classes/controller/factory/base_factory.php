<?php
    namespace controller\factory;

    use model\user;
    use mvc\interfaces\factory;
    use mvc\view;

    class base_factory implements factory {
        public function __invoke($container, $controller) {
            if (method_exists($this, 'create')) {
                $controller = $this->create($container, $controller);
            } else {
                $controller = new $controller();
            }

            $this->set_objects($container, $controller);
            return $controller;
        }

        protected function set_objects($container, $controller) {
            $controller->set_request($container->get('request'));
            $controller->set_response($container->get('response'));
            $controller->set_session($container->get('session'));
            $controller->set_config($container->get('config'));
            $controller->set_user(new user($container->get('db'), $container->get('session')));
            $controller->set_view(new view());
        }
    }
