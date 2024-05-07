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
     * @param array<array<mixed>> $matchedRoute
     */
    public function process(array &$matchedRoute): void
    {
        echo '<pre>' . print_r($matchedRoute, true) . '</pre>';
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

