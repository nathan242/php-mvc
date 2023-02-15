<?php

namespace Application\Controller;

use Framework\Gui\Form;

class FormTest extends BaseAuthController
{
    private $form;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function init()
    {
        parent::init();
        $this->view->setView('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', '/main'], ['Form Test', $_SERVER['REQUEST_URI']]]]);

        $this->form->init('test');
        $this->form->input('data1', 'data1', 'text', true);
        $this->form->input('data2', 'data2', 'text', true);
    }

    public function get()
    {
        return $this->response->set(200, $this->view->get('form_test.phtml', ['form' => $this->form, 'data' => '']));
    }

    public function post()
    {
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
