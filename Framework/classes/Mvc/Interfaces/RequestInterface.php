<?php

namespace Framework\Mvc\Interfaces;

/**
 * Request interface
 *
 * @package Framework\Mvc\Interfaces
 * @property string $method
 * @property string $remoteAddr
 * @property string $remotePort
 * @property int $requestTime
 * @property string $protocol
 * @property string $path
 * @property array<string, array<mixed>> $params
 * @property string $body
 */
interface RequestInterface
{
    /**
     * Get request data
     */
    public function get(): void;

    /**
     * Get request parameter
     *
     * @param string $name
     * @param mixed $default
     * @param string|null $type
     * @return mixed
     */
    public function param(string $name, mixed $default =  null, string $type = null): mixed;

    /**
     * Get all request parameters
     *
     * @param array<string> $order
     * @return array<string, mixed>
     */
    public function allParams(array $order = ['GET', 'POST']): array;

    /**
     * Check if request parameter exists
     *
     * @param string $name
     * @param string $type
     * @return bool
     */
    public function hasParam(string $name, string $type = null): bool;

    /**
     * Get information about files sent in request
     *
     * @return array<string>
     */
    public function files(): array;

    /**
     * Store file sent in request
     *
     * @param string|null $name
     * @param string $dest
     * @return bool
     */
    public function storeFile(string|null $name, string $dest): bool;
}
