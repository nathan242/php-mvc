<?php

namespace Application\Controller;

use Framework\Gui\Form;
use Framework\Mvc\FileOutput;

class Files extends BaseAuthController
{
    private $form;
    private $file_output;
    private $upload_dir;

    public function __construct(Form $form, FileOutput $file_output)
    {
        $this->form = $form;
        $this->file_output = $file_output;
    }

    public function init()
    {
        parent::init();

        $this->upload_dir = $this->config->get('application')['upload_dir'];

        $this->view->setView('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', '/main'], ['Files', $_SERVER['REQUEST_URI']]]]);

        $this->form->init('Upload file', 'Upload', 'primary', 'post', ['enctype' => 'multipart/form-data']);
        $this->form->input('file_upload', 'Upload', 'file');
    }

    public function index()
    {
        $files = scandir($this->upload_dir);
        $files = array_diff($files, ['.', '..']);

        return $this->response->set(200, $this->view->get('files.phtml', ['files' => $files, 'form' => $this->form]));
    }

    public function upload()
    {
        $files = $this->request->files();

        if (count($files) === 0) {
            return $this->response->set(500, 'No file uploaded');
        }

        $result = $this->request->storeFile($files[0], $this->upload_dir . '/' . $files[0]);

        if (!$result) {
            return $this->response->set(500, 'Failed to save file');
        }

        return $this->response->set(302, '', ['Location' => 'file_upload']);
    }

    public function download($name)
    {
        $path = "{$this->upload_dir}/{$name}";
        if (!file_exists($path)) {
            return $this->response->set(404, 'File not found');
        }

        return $this->response->set(200, $this->file_output->set($path), $this->file_output->getHeaders());
    }
}

