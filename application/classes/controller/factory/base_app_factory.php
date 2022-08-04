<?php
    namespace application\controller\factory;

    use framework\controller\factory\base_factory;
    use application\model\user;

    class base_app_factory extends base_factory {
        protected function set_objects($container, $class) {
            $class->set_user($container->get(user::class));
            parent::set_objects($container, $class);
        }
    }

