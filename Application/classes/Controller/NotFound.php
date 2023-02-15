<?php

namespace Application\Controller;

use Framework\Controller\BaseController;

class NotFound extends BaseController
{
    public function error_404($route)
    {
        $this->view->setView('404.phtml', ['route' => $route]);
        return $this->response->set(404, $this->view);
    }
}

