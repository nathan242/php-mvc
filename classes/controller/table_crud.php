<?php
    namespace controller;

    use controller\traits\login_required;
    use gui\form;
    use model\test;

    class table_crud extends base_controller {
        use login_required;

        private $form;
        private $model;

        public function __construct(form $form, test $model) {
            $this->form = $form;
            $this->model = $model;
        }

        public function init() {
            $this->view->set_view('template.php', ['topbar' => true, 'loginuser' => $this->session->get('loginuser'), 'pagepath' => [['MAIN', '/main'], ['Table CRUD', '/table_crud']]]);
            parent::init();
        }

        public function list_all() { // @todo: fix
            return $this->response->set(200, $this->view->get('table_crud.php', ['records' => $data]));
        }

        public function create() {
            $this->form->init('New record');
            $this->form->input('text', 'Text', 'text', true);
            $this->form->input('number', 'Number', 'text', true);

            $result = $this->form->handle(
                $this->request->params['POST'],
                function ($model, $data) {
                    $model->text = $data['text'];
                    $model->number = $data['number'];
                    return $model->insert();
                },
                [$this->model]
            );

            if (!$result) {
                $this->view->pagepath = array_merge($this->view->pagepath, [['New', $_SERVER['REQUEST_URI']]]);
                return $this->response->set(200, $this->view->get('table_crud_edit.php', ['form' => $this->form]));
            }

            return $this->response->set(302, '', ['Location' => '/table_crud']);
        }

        public function edit($id) {
            if (!$this->model->retrieve($id)) {
                return $this->response->set(404, 'Record not found');
            }

            $this->form->init("Edit record {$id}");
            $this->form->input('text', 'Text', 'text', true, $this->model->text);
            $this->form->input('number', 'Number', 'text', true, $this->model->number);

            $result = $this->form->handle(
                $this->request->params['POST'],
                function ($id, $model, $data) {
                    $model->text = $data['text'];
                    $model->number = $data['number'];
                    return $model->update();
                },
                [$id, $this->model]
            );

            if (!$result) {
                $this->view->pagepath = array_merge($this->view->pagepath, [["Edit {$id}", $_SERVER['REQUEST_URI']]]);
                return $this->response->set(200, $this->view->get('table_crud_edit.php', ['form' => $this->form]));
            }

            if (!$this->form->result) {
                return $this->response->set(404, 'Record not found');
            }

            return $this->response->set(302, '', ['Location' => '/table_crud']);
        }
    }
