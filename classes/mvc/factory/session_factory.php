<?php
    namespace mvc\factory;

    use mvc\interfaces\factory;

    class session_factory implements factory {

        public function __invoke($container, $controller) {
            $config = $container->get('config');
            return new $controller($config->get('application')['name']);
        }
    }