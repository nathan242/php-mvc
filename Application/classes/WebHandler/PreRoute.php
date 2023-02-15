<?php

namespace Application\WebHandler;

use Framework\Mvc\Interfaces\RequestInterface;
use Framework\Mvc\Interfaces\WebHandler\PreRouteInterface;

class PreRoute implements PreRouteInterface
{
    public function process(RequestInterface $request)
    {
        $request->params['COOKIE']['inserted'] = 'Cookie inserted into request via preroute';
    }
}

