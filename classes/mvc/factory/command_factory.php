<?php
    namespace mvc\factory;

    use mvc\interfaces\container_interface;
    use mvc\interfaces\factory_interface;

    class command_factory implements factory_interface {

        public function __invoke(container_interface $container, $controller) {
            $config = $container->get('config');
            return new $controller($container, $config->get('commands'), $config->get('application'));
        }
    }