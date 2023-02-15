<?php

namespace Application\Controller;

class Main extends BaseAuthController
{
    protected $view;

    public function init()
    {
        parent::init();
        $this->view->setView('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', $_SERVER['REQUEST_URI']]]]);
    }

    public function main()
    {
        return $this->response->set(200, $this->view->get('main.phtml'));
    }
}
