<?php

namespace Application\WebHandler;

use Framework\Mvc\Interfaces\WebHandler\PreActionInterface;

class PreAction implements PreActionInterface
{
    public function process(array &$matched_route)
    {
        echo '<pre>' . print_r($matched_route, 1) . '</pre>';
        /*
        $matched_route = [
            [
                'not_found',
                'error_404'
            ],
            [
                'Injected via preaction'
            ]
        ];
        */
    }
}

