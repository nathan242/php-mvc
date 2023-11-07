<?php
namespace Soap\Soap\Factory;

use SoapServer;

class SoapServerFactory
{
    public function create(string $wsdl = null, array $options = []): SoapServer
    {
        return new SoapServer($wsdl, $options);
    }
}
