<?php

namespace Framework\Command;

use Framework\Mvc\Interfaces\ConfigInterface;

/**
 * Base class that all command controllers extend from
 *
 * @package Framework\Command
 */
abstract class BaseCommand
{
    /** @var ConfigInterface $config*/
    protected $config;

    /**
     * Set the config object
     *
     * @param ConfigInterface $config
     */
    public function setConfig(ConfigInterface $config): void
    {
        $this->config = $config;
    }
}
