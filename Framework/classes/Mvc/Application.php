<?php

namespace Framework\Mvc;

use Framework\Mvc\Interfaces\ConfigInterface;
use Framework\Mvc\Interfaces\ContainerInterface;
use Framework\Mvc\Interfaces\RequestInterface;
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
    /** @var array<string, mixed> $localConfig */
    protected $localConfig;

    /** @var ConfigInterface $config */
    protected $config;

    /** @var ContainerInterface $container */
    protected $container;

    /**
     * Initialize application
     *
     * @param string $rootPath Path to application directory
     * @param array<string, mixed> $localConfig Local configuration
     * @return self
     */
    public function init(string $rootPath, array $localConfig = []): self
    {
        $localConfig['root_path'] = $rootPath;
        $this->localConfig = $localConfig;
        $this->config = $this->getConfigInstance();
        $this->container = $this->getContainerInstance();
        $this->container->set(get_class($this->config), $this->config);
        $this->container->set(get_class($this->container), $this->container);
        $this->container->set(get_class($this), $this);

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
    public function getConfig(): ConfigInterface|null
    {
        return $this->config;
    }

    /**
     * Get container instance
     *
     * @return ContainerInterface|null
     */
    public function getContainer(): ContainerInterface|null
    {
        return $this->container;
    }

    /**
     * Run CLI command
     *
     * @param array<string> $args Command arguments
     * @param bool $throwExceptions Set true to pass exceptions on
     * @return int Return code
     * @throws CommandControllerNotFound
     * @throws CommandMethodNotFound
     * @throws CommandNotFound
     */
    public function runCli(array $args = [], bool $throwExceptions = false): int
    {
        try {
            return $this->container->get(CliHandler::class)->process($args);
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
     * @return ResponseInterface|null
     * @throws ControllerNotFound
     * @throws MethodNotFound
     * @throws PageNotFound
     */
    public function runWeb($request = null, bool $returnResponse = false, bool $throwExceptions = false): ?ResponseInterface
    {
        try {
            if ($request === null) {
                $request = $this->container->get(RequestInterface::class);
                $request->get();
            } else {
                $this->container->set(get_class($request), $request);
            }

            $response = $this->container->get(WebHandler::class)->process($request);
        } catch (PageNotFound $e) {
            if ($throwExceptions) throw $e;
            $response = $this->container->get(ResponseInterface::class)->set(404, 'Page not found');
        } catch (MethodNotFound $e) {
            if ($throwExceptions) throw $e;
            $response = $this->container->get(ResponseInterface::class)->set(500, 'Internal error');
        } catch (ControllerNotFound $e) {
            if ($throwExceptions) throw $e;
            $response = $this->container->get(ResponseInterface::class)->set(500, 'Internal error');
        }

        if ($returnResponse) {
            return $response;
        }

        $response->send();

        return null;
    }
}

