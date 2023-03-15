<?php

namespace Framework\Mvc;

use Framework\Mvc\Interfaces\ResponseContentInterface;
use Framework\Mvc\Interfaces\ViewInterface;
use RuntimeException;

/**
 * View class
 *
 * @package Framework\Mvc
 */
class View implements ViewInterface
{
    /** @var string $viewPath */
    protected $viewPath;

    /** @var array<string, mixed> $viewVariables */
    protected $viewVariables;

    /** @var string|ResponseContentInterface */
    protected $viewView;

    /**
     * View constructor
     *
     * @param array<string, mixed> $config
     */
    public function __construct(array $config = [])
    {
        if (array_key_exists('path', $config)) {
            $this->viewPath = $config['path'];
        }
    }

    /**
     * Set base view
     *
     * @param string $view
     * @param array<string, mixed> $variables
     * @return $this
     */
    public function setView(string $view, array $variables = [])
    {
        $this->viewView = $view;
        $this->viewVariables = $variables;
        return $this;
    }

    /**
     * Update view variables
     *
     * @param array<string, mixed> $variables
     * @return $this
     */
    public function variables(array $variables = [])
    {
        $this->viewVariables = array_merge($this->viewVariables, $variables);
        return $this;
    }

    /**
     * Set view for variable
     *
     * @param string $view
     * @param array<string, mixed> $variables
     * @param string $name
     * @return $this
     */
    public function subView(string $view, array $variables, string $name)
    {
        $this->variables([$name => self::set(['path' => $this->viewPath], $view, $variables)]);
        return $this;
    }

    /**
     * Get view with subview
     *
     * @param string $view
     * @param array<string, mixed> $variables
     * @param string $name
     * @return $this
     */
    public function get(string $view, array $variables = [], string $name = 'view')
    {
        $this->subView($view, $variables, $name);
        return $this;
    }

    /**
     * Output response content
     */
    public function outputContent()
    {
        if (null === $this->viewPath) {
            throw new RuntimeException('View path is not configured');
        }

        foreach ($this->viewVariables as $key => $value) {
            if (!isset($$key)) {
                $$key = $value;
            }
        }

        require $this->viewPath . $this->viewView;
    }

    /**
     * Create a view object
     *
     * @param array<string, mixed> $config
     * @param string $view
     * @param array<string, mixed> $variables
     * @return View
     */
    public static function set(array $config, string $view, array $variables = [])
    {
        $viewObj = new self($config);
        $viewObj->setView($view, $variables);
        return $viewObj;
    }

    /**
     * Render a view
     *
     * @param array<string, mixed> $config
     * @param string $view
     * @param array<string, mixed> $variables
     */
    public static function render(array $config, string $view, array $variables = [])
    {
        self::set($config, $view, $variables)->outputContent();
    }

    /**
     * Get view variable
     *
     * @param mixed $name
     * @return mixed|null
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->viewVariables)) {
            return $this->viewVariables[$name];
        }

        return null;
    }

    /**
     * Set view variable
     *
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->viewVariables[$name] = $value;
    }

    /**
     * Check if view variable is set
     *
     * @param mixed $name
     * @return bool
     */
    public function __isset($name): bool
    {
        return array_key_exists($name, $this->viewVariables);
    }

    /**
     * Get response content as string
     *
     * @return string
     */
    public function __toString(): string
    {
        ob_start();
        $this->outputContent();
        return ob_get_clean();
    }
}
