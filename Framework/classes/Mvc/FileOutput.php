<?php

namespace Framework\Mvc;

use Framework\Mvc\Interfaces\ResponseContentInterface;
use RuntimeException;

/**
 * File download output for web response
 *
 * @package Framework\Mvc
 */
class FileOutput implements ResponseContentInterface
{
    /** @var string $path */
    protected $path;

    /** @var resource $handle */
    protected $handle;

    /**
     * Set file for response
     *
     * @param string $path
     * @return $this
     */
    public function set(string $path): self
    {
        $this->handle = fopen($path, 'r');

        if ($this->handle === false) {
            throw new RuntimeException("Failed to open file {$path} for response");
        }

        $this->path = $path;

        return $this;
    }

    /**
     * Get response headers for file download
     *
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        $headers = [];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $filename = basename($this->path);

        $headers['Content-type'] = finfo_file($finfo, $this->path);
        $headers['Content-Disposition'] = 'attachment; filename="' . $filename . '"';

        return $headers;
    }

    /**
     * Output file data
     */
    public function outputContent()
    {
        echo fread($this->handle, filesize($this->path));
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

