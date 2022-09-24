<?php
    namespace framework\db\factory;

    use framework\mvc\interfaces\container_interface;
    use framework\mvc\interfaces\factory_interface;

    class sql_builder_factory implements factory_interface {
        public function __invoke(container_interface $container, $class) {
            $config = $container->get('config');
            $db_config = $config->get('db');

            return $container->get($db_config['sql_builder']);
        }
    }

