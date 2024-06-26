<?php

namespace Framework\Mvc;

use Framework\Mvc\Interfaces\ResponseContentInterface;
use Framework\Mvc\Interfaces\ResponseInterface;

/**
 * Web response
 *
 * @package Framework\Mvc
 */
class Response implements ResponseInterface
{
    /** @var int $code */
    public $code = 200;

    /** @var ResponseContentInterface|string $content */
    public $content = '';

    /** @var array<string, mixed> $headers */
    public $headers = [];

    /** @var array<string, array<string, mixed>> $cookies */
    public $cookies = [];

    /**
     * Output response
     */
    public function send(): void
    {
        foreach ($this->cookies as $name => $cookie) {
            setcookie($name, $cookie['value'], $cookie['expires'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['http_only']);
        }

        http_response_code($this->code);
        foreach ($this->headers as $header => $value) {
            header($header . ': ' . $value);
        }

        if (is_object($this->content) && $this->content instanceof ResponseContentInterface) {
            $this->content->outputContent();
            return;
        }

        echo $this->content;
    }

    /**
     * Set response content
     *
     * @param int $code
     * @param ResponseContentInterface|string $content
     * @param array<string, mixed> $headers
     * @return self
     */
    public function set(int $code = 200, ResponseContentInterface|string $content = '', array $headers = []): self
    {
        $this->code = $code;
        $this->content = $content;
        $this->addHeaders($headers);

        return $this;
    }

    /**
     * Add response headers
     *
     * @param array<string, mixed> $headers
     * @return self
     */
    public function addHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

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
     * @return self
     */
    public function addCookie(string $name, string $value = '', int $expires = 0, string $path = '', string $domain = '', bool $secure = false, bool $httpOnly = false): self
    {
        $this->cookies[$name] = [
            'value' => $value,
            'expires' => $expires,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'http_only' => $httpOnly
        ];

        return $this;
    }
}
