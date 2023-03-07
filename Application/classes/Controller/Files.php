<?php

namespace Application\Controller;

use Framework\Gui\Form;
use Framework\Mvc\Exceptions\ResponseException;
use Framework\Mvc\FileOutput;
use Framework\Mvc\Interfaces\ResponseInterface;

/**
 * File upload/download controller
 *
 * @package Application\Controller
 */
class Files extends BaseAuthController
{
    /** @var Form $form */
    private $form;

    /** @var FileOutput $fileOutput */
    private $fileOutput;

    /** @var string $uploadDir */
    private $uploadDir;

    /**
     * Files constructor
     *
     * @param Form $form
     * @param FileOutput $fileOutput
     */
    public function __construct(Form $form, FileOutput $fileOutput)
    {
        $this->form = $form;
        $this->fileOutput = $fileOutput;
    }

    /**
     * Initialize class and form
     *
     * @throws ResponseException
     */
    public function init()
    {
        parent::init();

        $this->uploadDir = $this->config->get('application')['upload_dir'];

        $this->view->setView('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', '/main'], ['Files', $this->request->path]]]);

        $this->form->init('Upload file', 'Upload', 'primary', 'post', ['enctype' => 'multipart/form-data']);
        $this->form->input('file_upload', 'Upload', 'file');
    }

    /**
     * Get files page
     *
     * @return ResponseInterface
     */
    public function index(): ResponseInterface
    {
        $files = scandir($this->uploadDir);
        $files = array_diff($files, ['.', '..']);

        return $this->response->set(200, $this->view->get('files.phtml', ['files' => $files, 'form' => $this->form]));
    }

    /**
     * Upload file
     *
     * @return ResponseInterface
     */
    public function upload(): ResponseInterface
    {
        $files = $this->request->files();

        if (count($files) === 0) {
            return $this->response->set(500, 'No file uploaded');
        }

        $result = $this->request->storeFile($files[0], $this->uploadDir . '/' . $files[0]);

        if (!$result) {
            return $this->response->set(500, 'Failed to save file');
        }

        return $this->response->set(302, '', ['Location' => 'file_upload']);
    }

    /**
     * Download file
     *
     * @param string $name
     * @return ResponseInterface
     */
    public function download(string $name): ResponseInterface
    {
        $path = "{$this->uploadDir}/{$name}";
        if (!file_exists($path)) {
            return $this->response->set(404, 'File not found');
        }

        return $this->response->set(200, $this->fileOutput->set($path), $this->fileOutput->getHeaders());
    }
}

