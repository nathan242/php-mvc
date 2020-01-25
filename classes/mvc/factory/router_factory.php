<?php
    namespace mvc\factory;

    use mvc\interfaces\factory;

    class router_factory implements factory {

        public function __invoke($container, $controller) {
            $config = $container->get('config');
            return new $controller($container, $config->get('router'));
        }
    }