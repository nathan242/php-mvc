<?php

namespace Soap\Controller;

use Framework\Controller\BaseController;
use Framework\Mvc\Interfaces\ResponseInterface;
use Framework\Gui\Form;
use Soap\Soap\Client as ClientObj;
use Exception;

class Client extends BaseController
{
    protected $form;
    protected $client;

    public function __construct(Form $form, ClientObj $client)
    {
        $this->form = $form;
        $this->client = $client;
    }

    public function init(): void
    {
        parent::init();
        $this->view->SetView('template.phtml');
    }

    public function client(): ResponseInterface
    {
        $wsdlUrl = $this->request->param('wsdl_url', null);
        $wsdlInfo = [];
        $functions = [];
        $error = null;
        $response = null;

        $this->form->init('SOAP API Client', 'Submit', 'primary', 'get');
        $this->form->input('wsdl_url', 'WSDL URL: ', 'text', false, $wsdlUrl);

        if ($wsdlUrl !== null) {
            try {
                $this->client->wsdl($wsdlUrl);
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            $wsdlInfo = $this->client->getInfo();
            $functions = $this->client->getFunctionInfo();

            if ($this->request->method === 'POST') {
                $callFunction = $this->request->param('call_function', null, 'POST');
                if (array_key_exists($callFunction, $functions)) {
                    $callParams = [];
                    foreach ($functions[$callFunction] as $param) {
                        if ($this->request->param("array_{$callFunction}_{$param}", null, 'POST') !== null) {
                            $callParams[] = json_decode($this->request->param("param_{$callFunction}_{$param}", null, 'POST'), true);
                        } else {
                            $callParams[] = $this->request->param("param_{$callFunction}_{$param}", null, 'POST');
                        }
                    }

                    try {
                        $response = $this->client->call($callFunction, $callParams);
                    } catch (Exception $e) {
                        $error = $e->getMessage();
                    }
                }
            }
        }

        return $this->response->set(
            200,
            $this->view->get(
                'client.phtml',
                [
                    'form' => $this->form,
                    'function_defs' => $wsdlInfo['functions'] ?? null,
                    'type_defs' => $wsdlInfo['types'] ?? null,
                    'functions' => $functions,
                    'error' => $error,
                    'response' => $response
                ]
            )
        );
    }
}

