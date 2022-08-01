<?php
    namespace controller;

    use mvc\response;
    use mvc\view;
    use controller\traits\login_required;

    class main extends base_controller {
        use login_required;

        protected $view;

        public function init() {
            $this->view->set_view('template.php', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', $_SERVER['REQUEST_URI']]]]);
            parent::init();
        }

        public function main() {
            return $this->response->set(200, $this->view->get('main.php'));
        }
    }
