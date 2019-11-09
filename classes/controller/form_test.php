<?php
    namespace controller;

    use mvc\response;
    use mvc\view;
    use controller\traits\login_required;

    class form_test extends base_controller {
        use login_required;

        protected $view;
        private $form;

        public function __construct($form) {
            $this->form = $form;
        }

        public function init() {
            $this->view = view::set('template.php', ['topbar' => true, 'loginuser' => $this->session->get('loginuser'), 'pagepath' => [['MAIN', '/main'], ['Form Test', $_SERVER['REQUEST_URI']]]]);

            $this->form->init('test');
            $this->form->input('data1', 'data1', 'text', true);
            $this->form->input('data2', 'data2', 'text', true);

            parent::init();
        }

        public function get()
        {
            return response::set(200, $this->view->get('form_test.php', ['form' => $this->form, 'data' => '']));
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

            return response::set(200, $this->view->get('form_test.php', ['form' => $this->form, 'data' => $data]));
        }
    }
