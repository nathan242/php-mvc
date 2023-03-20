<?php

namespace Framework\Mvc\Interfaces\WebHandler;

use Framework\Mvc\Interfaces\RequestInterface;

/**
 * Web pre route interface
 *
 * @package Framework\Mvc\Interfaces\WebHandler
 */
interface PreRouteInterface
{
    /**
     * Process request
     *
     * @param RequestInterface $request
     */
    public function process(RequestInterface $request);
}
