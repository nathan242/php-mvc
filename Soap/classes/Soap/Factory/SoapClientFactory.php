<?php
namespace Soap\Soap\Factory;

use SoapClient;

class SoapClientFactory
{
    public function create($wsdl, $options = []): SoapClient
    {
        return new SoapClient($wsdl, $options);
    }
}

