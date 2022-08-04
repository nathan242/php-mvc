<?php
    namespace application\controller;

    use framework\controller\base_controller;

    abstract class base_app_controller extends base_controller {
        public function set_user($user) {
            $this->user = $user;
        }
    }

