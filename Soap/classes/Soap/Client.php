<?php
namespace Soap\Soap;

use Soap\Soap\Factory\SoapClientFactory;
use Exception;

class Client
{
    protected $soapClientFactory;
    protected $soapClient;
    protected $config;
    public $functionDefs;
    public $typeDefs;

    public function __construct(SoapClientFactory $soapClientFactory, array $config = [])
    {
        $this->soapClientFactory = $soapClientFactory;
        $this->config = $config;
    }

    public function setOption(string $name, mixed $value): void
    {
        if (!array_key_exists('options', $this->config)) {
            $this->config['options'] = [];
        }

        if ($value === null || $value === '') {
            unset($this->config['options'][$name]);
        } else {
            $this->config['options'][$name] = $value;
        }
    }

    public function getOption(string $name): mixed
    {
        if (!array_key_exists($name, $this->config['options'])) {
            return null;
        }

        return $this->config['options'][$name] ?? null;
    }

    public function wsdl(string $wsdl): void
    {
        $this->soapClient = null;
        $this->functionDefs = null;
        $this->typeDefs = null;

        try {
            $this->soapClient = $this->soapClientFactory->create($wsdl, $this->config['options'] ?? []);
            $this->functionDefs = $this->soapClient->__getFunctions();
            $this->typeDefs = $this->soapClient->__getTypes();
        } catch (Exception $e) {
            $this->soapClient = null;
            $this->functionDefs = null;
            $this->typeDefs = null;

            throw $e;
        }
    }

    public function getInfo(): array
    {
        return [
            'functions' => $this->functionDefs,
            'types' => $this->typeDefs
        ];
    }

    public function getFunctionInfo(): array
    {
        $functions = [];

        if (is_array($this->functionDefs)) {
            foreach ($this->functionDefs as $def) {
                $matches = [];
                if (preg_match('/(?<=\ )[a-zA-Z0-9_]+\([^\)]*\)/', $def, $matches)) {
                    $params = [];
                    preg_match_all('/\$[a-zA-Z0-9_]+/', $matches[0], $params);
                    $functionName = explode('(', $matches[0])[0];
                    $functions[$functionName] = $params[0];
                }
            }
        }

        return $functions;
    }

    public function call(string $function, array $params)
    {
        return $this->soapClient->{$function}(...$params);
    }

    public function getRawData(): array
    {
        return [
            'request' => $this->soapClient->__getLastRequest(),
            'response' => $this->soapClient->__getLastResponse()
        ];
    }
}

