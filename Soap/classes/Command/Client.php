<?php

namespace Soap\Command;

use Framework\Command\BaseCommand;
use Soap\Soap\Client as ClientObj;
use Exception;

class Client extends BaseCommand
{
    protected $client;

    public function __construct(ClientObj $client)
    {
        $this->client = $client;
    }

    protected function setWsdl(?string $wsdl): void
    {
        if ($wsdl === null) {
            throw new Exception('Please specify a WSDL URL');
        }

        $this->client->wsdl($wsdl);
    }

    public function info(array $args = []): int
    {
        try {
            $this->setWsdl($args[1] ?? null);
        } catch (Exception $e) {
            echo 'ERROR: '.$e->getMessage()."\n";
            return 1;
        }

        echo print_r($this->client->getInfo(), true)."\n";

        return 0;
    }
}

