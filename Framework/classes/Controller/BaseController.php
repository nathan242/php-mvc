<?php

namespace Framework\Controller;

use Framework\Mvc\Interfaces\RequestInterface;
use Framework\Mvc\Interfaces\ResponseInterface;
use Framework\Mvc\Interfaces\SessionInterface;
use Framework\Mvc\Interfaces\ConfigInterface;
use Framework\Mvc\Interfaces\ViewInterface;

/**
 * Base controller class that all web controllers extend from
 *
 * @package Framework\Controller
 */
abstract class BaseController
{
    /** @var RequestInterface */
    protected $request;

    /** @var ResponseInterface */
    protected $response;

    /** @var SessionInterface */
    protected $session;

    /** @var ConfigInterface */
    protected $config;

    /** @var ViewInterface */
    protected $view;

    /**
     * Set request object
     *
     * @param RequestInterface $request
     */
    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }

    /**
     * Set response object
     *
     * @param ResponseInterface $response
     */
    public function setResponse(ResponseInterface $response): void
    {
        $this->response = $response;
    }

    /**
     * Set session object
     *
     * @param SessionInterface $session
     */
    public function setSession(SessionInterface $session): void
    {
        $this->session = $session;
    }

    /**
     * Set config object
     *
     * @param ConfigInterface $config
     */
    public function setConfig(ConfigInterface $config): void
    {
        $this->config = $config;
    }

    /**
     * Set view object
     *
     * @param ViewInterface $view
     */
    public function setView(ViewInterface $view): void
    {
        $this->view = $view;
    }

    /**
     * Class initialize function
     *
     * Override this in implementing classes. Will be ran before the controller method is called.
     */
    public function init(): void
    {

    }
}
