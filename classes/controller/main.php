<?php
    namespace controller;

    use mvc\response;
    use mvc\view;
    use controller\traits\login_required;

    class main extends base_controller {
        use login_required;

        public function main() {
            $this->check_logged_in();
            return response::set(200, view::set('main.php', ['loginuser' => $this->session->get('loginuser')]));
        }
    }
