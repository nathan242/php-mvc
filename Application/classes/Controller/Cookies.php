<?php

namespace Application\Controller;

use Framework\Gui\Form;
use Framework\Mvc\Exceptions\ResponseException;
use Framework\Mvc\Interfaces\ResponseInterface;

/**
 * Cookies test controller
 *
 * @package Application\Controller
 */
class Cookies extends BaseAuthController
{
    /** @var Form $form */
    private $form;

    /**
     * Cookies constructor
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     * Initialize class and form
     *
     * @throws ResponseException
     */
    public function init()
    {
        parent::init();
        $this->view->setView('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', '/main'], ['Cookies', $this->request->path]]]);

        $this->form->init('Cookies');
        $this->form->input('name', 'Name:');
        $this->form->input('value', 'Value:', 'text', true);
    }

    /**
     * Cookies index page
     *
     * @return ResponseInterface
     */
    public function index(): ResponseInterface
    {
        $cookies = [];
        foreach ($this->request->params['COOKIE'] as $name => $value) {
            $cookies[] = ['Name' => $name, 'Value' => $value];
        }

        return $this->response->set(200, $this->view->get('cookies.phtml', ['cookies' => $cookies, 'form' => $this->form]));
    }

    /**
     * Save cookie
     *
     * @return ResponseInterface
     */
    public function save(): ResponseInterface
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

