<?php

namespace Application\Controller;

use Framework\Gui\Form;
use Framework\Mvc\Exceptions\ResponseException;
use Framework\Mvc\Interfaces\ResponseInterface;

/**
 * Form test
 *
 * @package Application\Controller
 */
class FormTest extends BaseAuthController
{
    /** @var Form $form */
    private $form;

    /**
     * FormTest constructor
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
        $this->view->setView('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', '/main'], ['Form Test', $this->request->path]]]);

        $this->form->init('test');
        $this->form->input('data1', 'data1', 'text', true);
        $this->form->input('data2', 'data2', 'text', true);
    }

    /**
     * Get form
     *
     * @return ResponseInterface
     */
    public function get(): ResponseInterface
    {
        return $this->response->set(200, $this->view->get('form_test.phtml', ['form' => $this->form, 'data' => '']));
    }

    /**
     * Hand form post
     *
     * @return ResponseInterface
     */
    public function post(): ResponseInterface
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
