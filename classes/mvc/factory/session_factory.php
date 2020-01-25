<?php
    namespace mvc\factory;

    use mvc\interfaces\container_interface;
    use mvc\interfaces\factory_interface;

    class session_factory implements factory_interface {

        public function __invoke(container_interface $container, $controller) {
            $config = $container->get('config');
            return new $controller($config->get('application')['name']);
        }
    }