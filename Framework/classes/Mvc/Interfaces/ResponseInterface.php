<?php

namespace Framework\Mvc\Interfaces;

/**
 * Response interface
 *
 * @package Framework\Mvc\Interfaces
 */
interface ResponseInterface
{
    /**
     * Output response
     */
    public function send(): void;

    /**
     * Set response content
     *
     * @param int $code
     * @param string|ResponseContentInterface $content
     * @param array<string, mixed> $headers
     * @return $this
     */
    public function set(int $code = 200, $content = '', array $headers = []);

    /**
     * Add response headers
     *
     * @param array<string, mixed> $headers
     * @return $this
     */
    public function addHeaders(array $headers);

    /**
     * Add response cookie
     *
     * @param string $name
     * @param string $value
     * @param int $expires
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return $this
     */
    public function addCookie(string $name, string $value = '', int $expires = 0, string $path = '', string $domain = '', bool $secure = false, bool $httpOnly = false);
}
