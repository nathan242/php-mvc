<?php
    namespace application\web_handler;

    use framework\mvc\interfaces\web_handler\preaction_interface;
    use framework\mvc\interfaces\request_interface;

    class preaction implements preaction_interface {
        public function process(&$matched_route) {
            echo '<pre>'.print_r($matched_route, 1).'</pre>';
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

