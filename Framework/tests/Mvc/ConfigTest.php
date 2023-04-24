<?php

use Framework\Mvc\Config;

class ConfigTest extends BaseTestCase
{
    public function testMissingConfigDir()
    {
        $this->expectException(RuntimeException::class);
        new Config('non-existing');
    }

    public function testLocalConfig()
    {
        $local = ['test' => 'this'];
        $config = new Config(__DIR__.'/test_config', $local);

        $this->assertEquals($local, $config->local, "Local config not accessible or correct");
    }

    public function testGetConfig()
    {
        $local = ['test' => 'test_value'];
        $config = new Config(__DIR__.'/test_config', $local);

        $testConfig = $config->get('test');
        $this->assertEquals(
            [
                'key' => 'value',
                'local_key' => $local['test']
            ],
            $testConfig,
            "Retrieved test config does not match"
        );
    }

    public function testGetMissingConfig()
    {
        $local = ['test' => 'test_value'];
        $config = new Config(__DIR__.'/test_config', $local);

        $testConfig = $config->get('missing');
        $this->assertEquals([], $testConfig, "Retrieved missing config was incorrect");
    }
}

