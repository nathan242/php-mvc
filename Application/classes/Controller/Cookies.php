<?php

namespace Application\Controller;

use Framework\Gui\Form;

class Cookies extends BaseAuthController
{
    private $form;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function init()
    {
        parent::init();
        $this->view->setView('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', '/main'], ['Cookies', $this->request->path]]]);

        $this->form->init('Cookies');
        $this->form->input('name', 'Name:');
        $this->form->input('value', 'Value:', 'text', true);
    }

    public function index()
    {
        $cookies = [];
        foreach ($this->request->params['COOKIE'] as $name => $value) {
            $cookies[] = ['Name' => $name, 'Value' => $value];
        }

        return $this->response->set(200, $this->view->get('cookies.phtml', ['cookies' => $cookies, 'form' => $this->form]));
    }

    public function save()
    {
        $this->form->handle(
            $this->request->params['POST'],
            function ($response, $data) {
                if ($data['value'] === '') {
                    $response->addCookie($data['name'], '', time() - 3600);
                } else {
                    $response->addCookie($data['name'], $data['value']);
                }
            },
            [$this->response]
        );

        return $this->response->set(302, '', ['Location' => 'cookies']);
    }
}

