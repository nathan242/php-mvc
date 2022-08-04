<?php
    namespace application\controller;

    use framework\mvc\response;
    use framework\mvc\view;

    class main extends base_auth_controller {
        protected $view;

        public function init() {
            parent::init();
            $this->view->set_view('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', $_SERVER['REQUEST_URI']]]]);
        }

        public function main() {
            return $this->response->set(200, $this->view->get('main.phtml'));
        }
    }
