<?php
    namespace controller\factory;

    use model\user;
    use mvc\interfaces\container_interface;
    use mvc\interfaces\factory_interface;
    use mvc\view;

    class base_factory implements factory_interface {
        public function __invoke(container_interface $container, $class) {
            if (method_exists($this, 'create')) {
                $class = $this->create($container, $class);
            } else {
                $class = new $class();
            }

            $this->set_objects($container, $class);
            return $class;
        }

        protected function set_objects($container, $class) {
            $class->set_request($container->get('request'));
            $class->set_response($container->get('response'));
            $class->set_session($container->get('session'));
            $class->set_config($container->get('config'));
            $class->set_user($container->get(user::class));
            $class->set_view(new view());
        }
    }
