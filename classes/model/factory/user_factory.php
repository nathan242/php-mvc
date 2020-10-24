<?php
    namespace model\factory;

    use model\user;
    use model\model_collection;
    use mvc\interfaces\container_interface;
    use mvc\interfaces\factory_interface;

    class user_factory implements factory_interface {
        public function __invoke(container_interface $container, $class) {
            return new user($container->get('db_driver'), $container->get('db_sql_builder'), $container->get(model_collection::class), $container->get('session'));
        }
    }

