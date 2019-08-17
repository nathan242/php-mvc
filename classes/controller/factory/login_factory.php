<?php
    namespace controller\factory;

    use mvc\object_storage;
    use db\db_factory;
    use model\user;

    class login_factory extends base_factory {
        public function create($controller) {
            return new $controller(new user(db_factory::get(object_storage::get('config')), object_storage::get('session')));
        }
    }
