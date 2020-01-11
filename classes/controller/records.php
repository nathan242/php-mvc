<?php
    namespace controller;

    use mvc\response;
    use mvc\view;
    use controller\traits\login_required;

    class records extends base_controller {
        use login_required;

        protected $view;
        private $form;

        public function __construct($form) {
            $this->form = $form;
        }

        public function init() {
            $this->view->set_view('template.php', ['topbar' => true, 'loginuser' => $this->session->get('loginuser'), 'pagepath' => [['MAIN', '/main'], ['Records', '/records']]]);
            parent::init();
        }

        private function get_records() {
            $records = $this->session->get('records');
            if (false === $records) {
                $records = [];
            }

            return $records;
        }

        public function list_all() {
            $records = $this->get_records();

            $data = [];
            foreach ($records as $key => $value) {
                $data[] = [$key, $value];
            }

            return $this->response->set(200, $this->view->get('records.php', ['records' => $data]));
        }

        public function create() {
            $this->form->init('New record');
            $this->form->input('value', 'value', 'text', true);

            $result = $this->form->handle(
                $this->request->params['POST'],
                function ($session, $data) {
                    $records = $session->get('records');
                    if (false === $records) {
                        $records = [];
                    }

                    $records[] = $data['value'];
                    $session->set('records', $records);

                    return true;
                },
                [$this->session]
            );

            if (!$result) {
                $this->view->pagepath = array_merge($this->view->pagepath, [['New', $_SERVER['REQUEST_URI']]]);
                return $this->response->set(200, $this->view->get('records_edit.php', ['form' => $this->form]));
            }

            return $this->response->set(302, '', ['Location' => '/records']);
        }

        public function edit($id) {
            $records = $this->get_records();
            if (!array_key_exists($id, $records)) {
                return $this->response->set(404, 'Record not found');
            }

            $this->form->init("Edit record {$id}");
            $this->form->input('value', 'value', 'text', true, $records[$id]);

            $result = $this->form->handle(
                $this->request->params['POST'],
                function ($id, $session, $data) {
                    $records = $session->get('records');
                    if (false === $records || !array_key_exists($id, $records)) {
                        return false;
                    }

                    $records[$id] = $data['value'];
                    $session->set('records', $records);

                    return true;
                },
                [$id, $this->session]
            );

            if (!$result) {
                $this->view->pagepath = array_merge($this->view->pagepath, [["Edit {$id}", $_SERVER['REQUEST_URI']]]);
                return $this->response->set(200, $this->view->get('records_edit.php', ['form' => $this->form]));
            }

            if (!$this->form->result) {
                return $this->response->set(404, 'Record not found');
            }

            return $this->response->set(302, '', ['Location' => '/records']);
        }
    }
