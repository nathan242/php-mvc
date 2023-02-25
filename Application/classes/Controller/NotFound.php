<?php

namespace Application\Controller;

use Framework\Controller\BaseController;
use Framework\Mvc\Interfaces\ResponseInterface;

/**
 * 404 page
 *
 * @package Application\Controller
 */
class NotFound extends BaseController
{
    /**
     * Render 404 page
     *
     * @param string $route
     * @return ResponseInterface
     */
    public function error404(string $route): ResponseInterface
    {
        $this->view->setView('404.phtml', ['route' => $route]);
        return $this->response->set(404, $this->view);
    }
}

