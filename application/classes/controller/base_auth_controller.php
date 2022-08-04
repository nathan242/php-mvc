<?php
    namespace application\controller;

    use framework\mvc\exceptions\response_exception;

    abstract class base_auth_controller extends base_app_controller {
        public function init() {
            if (!$this->user->check_logged_in()) {
                $this->session->destroy();

                throw new response_exception($this->response->set(302, '', ['Location' => '/']));
            }
        }
    }

