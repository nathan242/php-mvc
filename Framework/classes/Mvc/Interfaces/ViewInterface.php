<?php

namespace Framework\Mvc\Interfaces;

/**
 * View interface
 *
 * @package Framework\Mvc\Interfaces
 */
interface ViewInterface extends ResponseContentInterface
{
    /**
     * Set base view
     *
     * @param string $view
     * @param array<string, mixed> $variables
     * @return $this
     */
    public function setView(string $view, array $variables = []);

    /**
     * Update view variables
     *
     * @param array<string, mixed> $variables
     * @return $this
     */
    public function variables(array $variables = []);

    /**
     * Get view with subview
     *
     * @param string $view
     * @param array<string, mixed> $variables
     * @param string $name
     * @return $this
     */
    public function get(string $view, array $variables = [], string $name = 'view');

    /**
     * Get view variable
     *
     * @param mixed $name
     * @return mixed|null
     */
    public function __get($name);

    /**
     * Set view variable
     *
     * @param mixed $name
     * @param mixed $value
     */
    public function __set($name, $value): void;

    /**
     * Check if view variable is set
     *
     * @param mixed $name
     * @return bool
     */
    public function __isset($name): bool;
}
