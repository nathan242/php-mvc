<?php
namespace Soap\Server;

use Framework\Mvc\Application;

class Test
{
    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Test method
     *
     * @soap
     * @return string
     */
    public function helloWorld(): string
    {
        return 'Hello World!';
    }

    /**
     * Add numbers
     *
     * @soap
     * @param int $a
     * @param int $b
     * @return int
     */
    public function add(int $a, int $b): int
    {
        return $a+$b;
    }

    /**
     * Dump application
     *
     * @soap
     * @return string
     */
    public function dumpApplication(): string
    {
        return print_r($this->application, true);
    }

    /**
     * Run application CLI command
     *
     * @soap
     * @param array $params
     * @return array
     */
    public function callCli(array $params): array
    {
        ob_start();
        $rc = $this->application->runCli(['', ...$params]);

        return [
            'returnCode' => $rc,
            'output' => ob_get_clean()
        ];
    }
}

