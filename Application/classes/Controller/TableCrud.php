<?php

namespace Application\Controller;

use Framework\Gui\Form;
use Application\Model\Test;
use Framework\Mvc\Exceptions\ResponseException;
use Framework\Mvc\Interfaces\ResponseInterface;

/**
 * DB table CRUD test
 *
 * @package Application\Controller
 */
class TableCrud extends BaseAuthController
{
    /** @var Form $form */
    private $form;

    /** @var Test $model */
    private $model;

    /**
     * TableCrud constructor
     *
     * @param Form $form
     * @param Test $model
     */
    public function __construct(Form $form, Test $model)
    {
        $this->form = $form;
        $this->model = $model;
    }

    /**
     * Initialize class
     *
     * @throws ResponseException
     */
    public function init(): void
    {
        parent::init();
        $this->view->setView('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', '/main'], ['Table CRUD', '/table_crud']]]);
    }

    /**
     * List all records
     *
     * @return ResponseInterface
     */
    public function listAll(): ResponseInterface
    {
        $all_records = $this->model->all()->toArray();
        return $this->response->set(200, $this->view->get('table_crud.phtml', ['records' => $all_records]));
    }

    /**
     * Create record
     *
     * @return ResponseInterface
     */
    public function create(): ResponseInterface
    {
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
            $this->view->pagepath = array_merge($this->view->pagepath, [['New', $this->request->path]]);
            return $this->response->set(200, $this->view->get('table_crud_edit.phtml', ['form' => $this->form]));
        }

        return $this->response->set(302, '', ['Location' => '/table_crud']);
    }

    /**
     * Edit record
     *
     * @param int $id
     * @return ResponseInterface
     */
    public function edit(int $id): ResponseInterface
    {
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
            $this->view->pagepath = array_merge($this->view->pagepath, [["Edit {$id}", $this->request->path]]);
            return $this->response->set(200, $this->view->get('table_crud_edit.phtml', ['form' => $this->form]));
        }

        if (!$this->form->result) {
            return $this->response->set(404, 'Record not found');
        }

        return $this->response->set(302, '', ['Location' => '/table_crud']);
    }
}
