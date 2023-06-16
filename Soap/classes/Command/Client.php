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

    public function call(array $args = []): int
    {
        try {
            $this->setWsdl($args[1] ?? null);
        } catch (Exception $e) {
            echo 'ERROR: '.$e->getMessage()."\n";
            return 1;
        }

        if (!isset($args[2])) {
            echo "ERROR: Please specify function\n";
            return 1;
        }

        $function = $args[2];

        array_shift($args);
        array_shift($args);
        array_shift($args);

        $params = [];

        foreach ($args as $arg) {
            if (!preg_match('/^[a-zA-Z0-9]*:.*$/', $arg)) {
                echo "ERROR: Parameter format must be <format>:<value>\n";
                return 1;
            }

            $paramParts = explode(':', $arg, 2);

            if ($paramParts[0] === 'a') {
                $params[] = json_decode($paramParts[1]);
            } else {
                $params[] = $paramParts[1];
            }
        }

        try {
            $response = $this->client->call($function, $params);
        } catch (Exception $e) {
            echo 'ERROR: '.$e->getMessage()."\n";
            return 1;
        }

        echo "RESPONSE:\n";
        echo print_r($response, true)."\n";

        return 0;
    }
}

