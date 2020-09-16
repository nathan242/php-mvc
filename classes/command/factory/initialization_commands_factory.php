<?php
    namespace command\factory;

    use mvc\interfaces\container_interface;
    use mvc\interfaces\factory_interface;

    class initialization_commands_factory extends base_factory implements factory_interface {
        public function create(container_interface $container, $class) {
            return new $class($container->get('db_driver'), $container->get('db_sql_builder'));
        }
    }
