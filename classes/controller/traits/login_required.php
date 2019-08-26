<?php
    namespace controller\traits;

    trait login_required {
        public function check_logged_in() {
            if (!$this->user->check_logged_in()) {
                $this->session->destroy();

                header('Location: /');
                exit();
            }
        }
    }
