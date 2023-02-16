<?php

namespace Framework\Mvc;

use Framework\Mvc\Interfaces\ConfigInterface;
use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\RequestInterface;
use Framework\Mvc\Exceptions\ResponseException;
use Framework\Mvc\Exceptions\PageNotFound;
use Framework\Mvc\Exceptions\MethodNotFound;
use Framework\Mvc\Exceptions\ControllerNotFound;
use Framework\Mvc\Exceptions\CommandNotFound;
use Framework\Mvc\Exceptions\CommandMethodNotFound;
use Framework\Mvc\Exceptions\CommandControllerNotFound;
use Framework\Mvc\Config;
use Framework\Mvc\Container;
use Framework\Mvc\Interfaces\ResponseInterface;

/**
 * Main application class
 *
 * @package Framework\Mvc
 */
class Application
{
    /** @var array $localConfig */
    protected $localConfig;

    /** @var ConfigInterface $config */
    protected $config;

    /** @var ContainerInterface $container */
    protected $container;

    /**
     * Initialize application
     *
     * @param string $rootPath Path to application directory
     * @param array $localConfig Local configuration
     * @return $this
     */
    public function init(string $rootPath, array $localConfig = [])
    {
        $localConfig['root_path'] = $rootPath;
        $this->localConfig = $localConfig;
        $this->config = $this->getConfigInstance();
        $this->container = $this->getContainerInstance();
        $this->container->set(get_class($this->config), $this->config);
        $this->container->set(get_class($this), $this);
        $this->container->set(get_class($this->container), $this->container);

        return $this;
    }

    /**
     * Create config instance
     *
     * @return ConfigInterface
     */
    protected function getConfigInstance(): ConfigInterface
    {
        if (array_key_exists('config_instance', $this->localConfig)) return $this->localConfig['config_instance'];

        $configPath = $this->localConfig['config_path'] ?? '/config';
        $class = $this->localConfig['config_class'] ?? Config::class;
        return new $class("{$this->localConfig['root_path']}{$configPath}", $this->localConfig);
    }

    /**
     * Create container instance
     *
     * @return ContainerInterface
     */
    protected function getContainerInstance(): ContainerInterface
    {
        if (array_key_exists('container_instance', $this->localConfig)) return $this->localConfig['container_instance'];

        $class = $this->localConfig['container_class'] ?? Container::class;
        return new $class($this->config->get('container'));
    }

    /**
     * Get config instance
     *
     * @return ConfigInterface|null
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get container instance
     *
     * @return ContainerInterface|null
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Run CLI command
     *
     * @param array $arg Command arguments
     * @param bool $throwExceptions Set true to pass exceptions on
     * @return int Return code
     * @throws CommandControllerNotFound
     * @throws CommandMethodNotFound
     * @throws CommandNotFound
     */
    public function runCli(array $arg = [], bool $throwExceptions = false)
    {
        try {
            return $this->container->get('cli_handler')->process($arg);
        } catch (CommandNotFound $e) {
            if ($throwExceptions) throw $e;
            echo "Command not found.\n";
            return 1;
        } catch (CommandMethodNotFound $e) {
            if ($throwExceptions) throw $e;
            echo "Command method not found.\n";
            return 2;
        } catch (CommandControllerNotFound $e) {
            if ($throwExceptions) throw $e;
            echo "Command controller not found.\n";
            return 2;
        }
    }

    /**
     * Handle web request
     *
     * @param RequestInterface|null $request Optional request
     * @param bool $returnResponse Return response object
     * @param bool $throwExceptions Set true to pass exceptions on
     * @return ResponseInterface|void
     * @throws ControllerNotFound
     * @throws MethodNotFound
     * @throws PageNotFound
     * @throws ResponseException
     */
    public function runWeb($request = null, bool $returnResponse = false, bool $throwExceptions = false)
    {
        try {
            if ($request === null) {
                $request = $this->container->get('request');
                $request->get();
            } else {
                $this->container->set(get_class($request), $request);
            }

            $response = $this->container->get('web_handler')->process($request);
        } catch (ResponseException $e) {
            if ($throwExceptions) throw $e;
            $response = $e->getResponse();
        } catch (PageNotFound $e) {
            if ($throwExceptions) throw $e;
            $response = $this->container->get('response')->set(404, 'Page not found');
        } catch (MethodNotFound $e) {
            if ($throwExceptions) throw $e;
            $response = $this->container->get('response')->set(500, 'Internal error');
        } catch (ControllerNotFound $e) {
            if ($throwExceptions) throw $e;
            $response = $this->container->get('response')->set(500, 'Internal error');
        }

        if ($returnResponse) {
            return $response;
        }

        $response->send();
    }
}

