<?php

namespace Framework\Mvc\Interfaces;

/**
 * Response content interface
 *
 * @package Framework\Mvc\Interfaces
 */
interface ResponseContentInterface
{
    /**
     * Output response content
     */
    public function outputContent(): void;

    /**
     * Get response content as string
     *
     * @return string
     */
    public function __toString(): string;
}
