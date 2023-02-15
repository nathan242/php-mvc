<?php

namespace Application\Controller;

use Framework\Gui\Form;

class Records extends BaseAuthController
{
    private $form;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function init()
    {
        parent::init();
        $this->view->setView('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', '/main'], ['Records', '/records']]]);
    }

    private function get_records()
    {
        $records = $this->session->records;
        if (null === $records) {
            $records = [];
        }

        return $records;
    }

    public function list_all()
    {
        $records = $this->get_records();

        $data = [];
        foreach ($records as $key => $value) {
            $data[] = [$key, $value];
        }

        return $this->response->set(200, $this->view->get('records.phtml', ['records' => $data]));
    }

    public function create()
    {
        $this->form->init('New record');
        $this->form->input('value', 'value', 'text', true);

        $result = $this->form->handle(
            $this->request->params['POST'],
            function ($session, $data) {
                $records = $session->records;
                if (false === $records) {
                    $records = [];
                }

                $records[] = $data['value'];
                $session->records = $records;

                return true;
            },
            [$this->session]
        );

        if (!$result) {
            $this->view->pagepath = array_merge($this->view->pagepath, [['New', $_SERVER['REQUEST_URI']]]);
            return $this->response->set(200, $this->view->get('records_edit.phtml', ['form' => $this->form]));
        }

        return $this->response->set(302, '', ['Location' => '/records']);
    }

    public function edit($id)
    {
        $records = $this->get_records();
        if (!array_key_exists($id, $records)) {
            return $this->response->set(404, 'Record not found');
        }

        $this->form->init("Edit record {$id}");
        $this->form->input('value', 'value', 'text', true, $records[$id]);

        $result = $this->form->handle(
            $this->request->params['POST'],
            function ($id, $session, $data) {
                $records = $session->records;
                if (false === $records || !array_key_exists($id, $records)) {
                    return false;
                }

                $records[$id] = $data['value'];
                $session->records = $records;

                return true;
            },
            [$id, $this->session]
        );

        if (!$result) {
            $this->view->pagepath = array_merge($this->view->pagepath, [["Edit {$id}", $_SERVER['REQUEST_URI']]]);
            return $this->response->set(200, $this->view->get('records_edit.phtml', ['form' => $this->form]));
        }

        if (!$this->form->result) {
            return $this->response->set(404, 'Record not found');
        }

        return $this->response->set(302, '', ['Location' => '/records']);
    }
}
