<?php

namespace Application\WebHandler;

use Framework\Mvc\Interfaces\WebHandler\PreActionInterface;

/**
 * Test pre action
 *
 * @package Application\WebHandler
 */
class PreAction implements PreActionInterface
{
    /**
     * @param array $matchedRoute
     */
    public function process(array &$matchedRoute)
    {
        echo '<pre>' . print_r($matchedRoute, 1) . '</pre>';
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

