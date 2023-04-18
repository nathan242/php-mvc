<?php
namespace Soap\Factory;

use SoapClient;

class SoapClientFactory {
    public function create($wsdl, $options = []) {
        return new SoapClient($wsdl, $options);
    }
}

