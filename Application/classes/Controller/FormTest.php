<?php

namespace Application\Controller;

use Framework\Gui\Form;
use Framework\Mvc\Exceptions\ResponseException;
use Framework\Mvc\Interfaces\ResponseInterface;
use Application\Exceptions\InvalidCsrfException;

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
    public function init(): void
    {
        parent::init();
        $this->view->setView('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', '/main'], ['Form Test', $this->request->path]]]);

        $this->form->init('Form Test');
        $this->form->input('csrf', 'csrf', 'hidden', false, $this->session->csrfToken);
        $this->form->input('text', 'text', 'text', true, $this->request->param('text'));
        $this->form->input('textarea', 'textarea', 'textarea', true, $this->request->param('textarea'));
        $this->form->input('select1', 'select1', 'select', true, $this->request->param('select1'), ['selects' => ['test0', 'test1', 'test2', 'test3']]);
        $this->form->input('select2', 'select2', 'select', true, $this->request->param('select2'), ['selects' => ['A' => 'testA', 'B' => 'testB', 'C' => 'testC', 'D' => 'testD']]);
        $this->form->input('checkbox', 'checkbox', 'checkbox', true, null, ['checked' => $this->request->param('checkbox') !== null]);
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
     * Handle form post
     *
     * @return ResponseInterface
     */
    public function post(): ResponseInterface
    {
        try {
            $result = $this->form->handle(
                $this->request->params['POST'],
                function (array $data) {
                    if ($data['csrf'] !== $this->session->csrfToken) {
                        throw new InvalidCsrfException();
                    }

                    return $data;
                }
            );
        } catch (InvalidCsrfException $e) {
            return $this->response->set(403, 'CSRF token mismatch');
        }

        if (!$result) {
            return $this->get();
        }

        $data = json_encode($this->form->result);

        return $this->response->set(200, $this->view->get('form_test.phtml', ['form' => $this->form, 'data' => $data]));
    }
}
