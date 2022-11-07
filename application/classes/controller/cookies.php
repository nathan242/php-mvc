<?php
    namespace application\controller;

    use framework\gui\form;

    class cookies extends base_auth_controller {
        private $form;

        public function __construct(form $form) {
            $this->form = $form;
        }

        public function init() {
            parent::init();
            $this->view->set_view('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', '/main'], ['Cookies', $this->request->path]]]);

            $this->form->init('Cookies');
            $this->form->input('name', 'Name:');
            $this->form->input('value', 'Value:');
        }

        public function index() {
            $cookies = [];
            foreach ($this->request->params['COOKIE'] as $name => $value) {
                $cookies[] = ['Name' => $name, 'Value' => $value];
            }

            return $this->response->set(200, $this->view->get('cookies.phtml', ['cookies' => $cookies, 'form' => $this->form]));
        }

        public function save() {
            $this->form->handle(
                $this->request->params['POST'],
                function ($response, $data) {
                     $response->add_cookie($data['name'], $data['value']);
                },
                [$this->response]
            );

            return $this->response->set(302, '', ['Location' => 'cookies']);
        }
    }

