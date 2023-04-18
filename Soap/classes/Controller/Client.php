<?php
namespace Soap\Controller;

use Framework\Controller\BaseController;
use Framework\Gui\Form;
use Soap\Factory\SoapClientFactory;
use Exception;

class Client extends BaseController {
    protected $form;
    protected $soapClientFactory;

    public function __construct(Form $form, SoapClientFactory $soapClientFactory) {
        $this->form = $form;
        $this->soapClientFactory = $soapClientFactory;
    }

    public function init() {
        parent::init();
        $this->view->SetView('template.phtml');
    }

    public function client() {
        $wsdlUrl = $this->request->param('wsdl_url', null);
        $functionDefs = null;
        $typeDefs = null;
        $functions = null;
        $error = null;
        $response = null;

        $this->form->init('SOAP API Client', 'Submit', 'primary', 'get');
        $this->form->input('wsdl_url', 'WSDL URL: ', 'text', false, $wsdlUrl);

        if ($wsdlUrl !== null) {
            try {
                $client = $this->soapClientFactory->create($wsdlUrl);
                $functionDefs = $client->__getFunctions();
                $typeDefs = $client->__getTypes();
            } catch (Exception $e) {
                $error = $e->getMessage();
            }

            if (is_array($functionDefs)) {
                foreach ($functionDefs as $def) {
                    $parts = explode(' ', $def);
                    $params = [];
                    preg_match_all('/\$[a-zA-Z0-9]+/', $def, $params);
                    $functionName = explode('(', $parts[1])[0];
                    $functions[$functionName] = $params[0];
                }
            }

            if ($this->request->method === 'POST') {
                $callFunction = $this->request->param('call_function', null, 'POST');
                if (array_key_exists($callFunction, $functions)) {
                    $callParams = [];
                    foreach ($functions[$callFunction] as $param) {
                        if ($this->request->param("array_{$callFunction}_{$param}", null, 'POST') !== null) {
                            $callParams[] = json_decode($this->request->param("param_{$callFunction}_{$param}", null, 'POST'), true);
                        } else {
                            $callParams[] = $this->request->param("param_{$param}", null, 'POST');
                        }
                    }

                    try {
                        //$response = $client->__soapCall($callFunction, $callParams);
                        $response = $client->{$callFunction}(...$callParams);
                    } catch (Exception $e) {
                        $error = $e->getMessage();
                    }
                }
            }
        }

        return $this->response->set(200, $this->view->get('client.phtml', ['form' => $this->form, 'function_defs' => $functionDefs, 'type_defs' => $typeDefs, 'functions' => $functions, 'error' => $error, 'response' => $response]));
    }
}

