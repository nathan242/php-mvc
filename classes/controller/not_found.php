<?php
    namespace controller;

    class not_found extends base_controller {
        public function error_404($route) {
            $this->view->set_view('404.php', ['route' => $route]);
            return $this->response->set(404, $this->view);
        }
    }

