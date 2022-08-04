<?php
    namespace application\controller;

    use framework\mvc\response;
    use framework\mvc\view;
    use framework\gui\form;

    class form_test extends base_auth_controller {
        private $form;

        public function __construct(form $form) {
            $this->form = $form;
        }

        public function init() {
            parent::init();
            $this->view->set_view('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', '/main'], ['Form Test', $_SERVER['REQUEST_URI']]]]);

            $this->form->init('test');
            $this->form->input('data1', 'data1', 'text', true);
            $this->form->input('data2', 'data2', 'text', true);
        }

        public function get()
        {
            return $this->response->set(200, $this->view->get('form_test.phtml', ['form' => $this->form, 'data' => '']));
        }

        public function post() {
            $result = $this->form->handle(
                $this->request->params['POST'],
                function ($data) {
                    return $data;
                }
            );

            if (!$result) {
                return $this->get();
            }

            $data = json_encode($this->form->result);

            return $this->response->set(200, $this->view->get('form_test.phtml', ['form' => $this->form, 'data' => $data]));
        }
    }
