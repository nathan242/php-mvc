<?php

namespace Framework\Mvc;

use Framework\Mvc\Interfaces\ConfigInterface;
use RuntimeException;

/**
 * Configuration class
 *
 * @package Framework\Mvc
 */
class Config implements ConfigInterface
{
    /** @var string $configPath */
    protected $configPath;

    /** @var array $local */
    public $local = [];

    /**
     * Config constructor
     *
     * @param string $configPath
     * @param array $local
     */
    public function __construct(string $configPath, array $local = [])
    {
        if (!is_dir($configPath)) {
            throw new RuntimeException("Configuration directory not found ($configPath)");
        }

        $this->configPath = $configPath;
        $this->local = $local;
    }

    /**
     * Get config
     *
     * @param string $name
     * @return array
     */
    public function get(string $name): array
    {
        $config = [];

        $file = "$this->configPath/$name.php";

        if (is_file($file)) {
            $local = $this->local;
            $config = require $file;
        }

        return $config;
    }
}

