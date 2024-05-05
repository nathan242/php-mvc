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
     * @return self
     */
    public function setView(string $view, array $variables = []): self;

    /**
     * Update view variables
     *
     * @param array<string, mixed> $variables
     * @return self
     */
    public function variables(array $variables = []): self;

    /**
     * Get view with subview
     *
     * @param string $view
     * @param array<string, mixed> $variables
     * @param string $name
     * @return self
     */
    public function get(string $view, array $variables = [], string $name = 'view'): self;

    /**
     * Get view variable
     *
     * @param mixed $name
     * @return mixed
     */
    public function __get(mixed $name): mixed;

    /**
     * Set view variable
     *
     * @param mixed $name
     * @param mixed $value
     */
    public function __set(mixed $name, mixed $value): void;

    /**
     * Check if view variable is set
     *
     * @param mixed $name
     * @return bool
     */
    public function __isset(mixed $name): bool;
}
