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
        $wsdlUrl = $this->request->param('wsdl_url');
        $wsdlInfo = [];
        $functions = [];
        $error = null;
        $response = null;
        $callFunction = null;
        $callParams = [];
        $showRaw = false;
        $rawData = [];

        foreach (
            [
                'option_login',
                'option_password',
                'option_proxy_host',
                'option_proxy_port',
                'option_proxy_login',
                'option_proxy_password'
            ]
            as $optionKey
        ) {
            if ($this->request->hasParam($optionKey)) {
                $key = explode('_', $optionKey, 2);
                $this->client->setOption($key[1], $this->request->param($optionKey));
            }
        }

        $this->form->init('SOAP API Client', 'Submit', 'primary', 'get');
        $this->form->input('wsdl_url', 'WSDL URL:', 'text', false, $wsdlUrl);
        $this->form->input('raw', 'Show raw:', 'checkbox', false, '1', ['checked' => $this->request->param('raw') == 1]);
        $this->form->input('option_login', 'HTTP auth user:', 'text', true, $this->client->getOption('login'));
        $this->form->input('option_password', 'HTTP auth password:', 'text', true, $this->client->getOption('password'));
        $this->form->input('option_proxy_host', 'Proxy host:', 'text', true, $this->client->getOption('proxy_host'));
        $this->form->input('option_proxy_port', 'Proxy port:', 'text', true, $this->client->getOption('proxy_port'));
        $this->form->input('option_proxy_login', 'Proxy login:', 'text', true, $this->client->getOption('proxy_login'));
        $this->form->input('option_proxy_password', 'Proxy password:', 'text', true, $this->client->getOption('proxy_password'));

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
                $showRaw = $this->request->param('raw') === '1' ? true : false;
                if (array_key_exists($callFunction, $functions)) {
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

                    if ($showRaw) {
                        $rawData = $this->client->getRawData();
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
                    'response' => $response,
                    'call_function' => $callFunction,
                    'call_params' => $callParams,
                    'raw_request' => $rawData['request'] ?? null,
                    'raw_response' => $rawData['response'] ?? null
                ]
            )
        );
    }
}

