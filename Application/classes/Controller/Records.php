<?php

namespace Application\Controller;

use Framework\Gui\Form;
use Framework\Mvc\Exceptions\ResponseException;
use Framework\Mvc\Interfaces\ResponseInterface;
use Application\Exceptions\InvalidCsrfException;
use Framework\Gui\Exceptions\InvalidFormData;
use Framework\Mvc\Interfaces\SessionInterface;

/**
 * Session records test
 *
 * @package Application\Controller
 */
class Records extends BaseAuthController
{
    /** @var Form $form */
    private $form;

    /**
     * Records constructor
     *
     * @param Form $form
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     * Initialize class
     *
     * @throws ResponseException
     */
    public function init(): void
    {
        parent::init();
        $this->view->setView('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', '/main'], ['Records', '/records']]]);
    }

    /**
     * Get records from session
     *
     * @return array<int, string>
     */
    private function getRecords(): array
    {
        $records = $this->session->records;
        if (null === $records) {
            $records = [];
        }

        return $records;
    }

    /**
     * List all records
     *
     * @return ResponseInterface
     */
    public function listAll(): ResponseInterface
    {
        $records = $this->getRecords();

        $data = [];
        foreach ($records as $key => $value) {
            $data[] = [$key, $value];
        }

        return $this->response->set(200, $this->view->get('records.phtml', ['records' => $data]));
    }

    /**
     * Create record
     *
     * @return ResponseInterface
     */
    public function create(): ResponseInterface
    {
        $this->form->init('New record');
        $this->form->input('csrf', 'csrf', 'hidden', false, $this->session->csrfToken);
        $this->form->input('value', 'value', 'text', true);

        try {
            $result = $this->form->handle(
                $this->request->params['POST'],
                function (SessionInterface $session, array $data) {
                    if ($data['csrf'] !== $this->session->csrfToken) {
                        throw new InvalidCsrfException();
                    }

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
        } catch (InvalidCsrfException $e) {
            return $this->response->set(403, 'CSRF token mismatch');
        } catch (InvalidFormData $e) {
            $result = false;
        }

        if (!$result) {
            $this->view->pagepath = array_merge($this->view->pagepath, [['New', $this->request->path]]);
            return $this->response->set(200, $this->view->get('records_edit.phtml', ['form' => $this->form]));
        }

        return $this->response->set(302, '', ['Location' => '/records']);
    }

    /**
     * Edit a record
     *
     * @param int $id
     * @return ResponseInterface
     */
    public function edit(int $id): ResponseInterface
    {
        $records = $this->getRecords();
        if (!array_key_exists($id, $records)) {
            return $this->response->set(404, 'Record not found');
        }

        $this->form->init("Edit record {$id}");
        $this->form->input('csrf', 'csrf', 'hidden', false, $this->session->csrfToken);
        $this->form->input('value', 'value', 'text', true, $records[$id]);

        try {
            $result = $this->form->handle(
                $this->request->params['POST'],
                function (int $id, SessionInterface $session, array $data) {
                    if ($data['csrf'] !== $this->session->csrfToken) {
                        throw new InvalidCsrfException();
                    }

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
        } catch (InvalidCsrfException $e) {
            return $this->response->set(403, 'CSRF token mismatch');
        } catch (InvalidFormData $e) {
            $result = false;
        }

        if (!$result) {
            $this->view->pagepath = array_merge($this->view->pagepath, [["Edit {$id}", $this->request->path]]);
            return $this->response->set(200, $this->view->get('records_edit.phtml', ['form' => $this->form]));
        }

        if (!$this->form->result) {
            return $this->response->set(404, 'Record not found');
        }

        return $this->response->set(302, '', ['Location' => '/records']);
    }
}
