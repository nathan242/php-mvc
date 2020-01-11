<?php
    namespace controller\traits;

    use mvc\exceptions\response_exception;

    trait login_required {
        public function check_logged_in() {
            if (!$this->user->check_logged_in()) {
                $this->session->destroy();

                throw new response_exception($this->response->set(302, '', ['Location' => '/']));
            }
        }
    }
