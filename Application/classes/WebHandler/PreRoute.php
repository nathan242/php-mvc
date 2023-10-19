<?php

namespace Application\WebHandler;

use Framework\Mvc\Interfaces\RequestInterface;
use Framework\Mvc\Interfaces\WebHandler\PreRouteInterface;

/**
 * Test pre route
 *
 * @package Application\WebHandler
 */
class PreRoute implements PreRouteInterface
{
    /**
     * @param RequestInterface $request
     */
    public function process(RequestInterface $request): void
    {
        $request->params['COOKIE']['inserted'] = 'Cookie inserted into request via preroute';
    }
}

