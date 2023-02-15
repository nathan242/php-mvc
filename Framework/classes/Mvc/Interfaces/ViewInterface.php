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
     * @param array $variables
     * @return $this
     */
    public function setView(string $view, array $variables = []);

    /**
     * Update view variables
     *
     * @param array $variables
     * @return $this
     */
    public function variables(array $variables = []);

    /**
     * Set view for variable
     *
     * @param string $view
     * @param array $variables
     * @param string $name
     * @return $this
     */
    public function subView(string $view, array $variables, string $name);

    /**
     * Get view with subview
     *
     * @param string $view
     * @param array $variables
     * @param string $name
     * @return $this
     */
    public function get(string $view, array $variables = [], string $name = 'view');
}