<?php
namespace Soap\Soap\Factory;

use PHP2WSDL\PHPClass2WSDL;

class PHP2WSDLFactory
{
    public function create(string $class, string $uri): PHPClass2WSDL
    {
        return new PHPClass2WSDL($class, $uri);
    }
}

