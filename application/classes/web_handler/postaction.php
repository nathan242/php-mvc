<?php
    namespace application\web_handler;

    use framework\mvc\interfaces\web_handler\postaction_interface;
    use framework\mvc\interfaces\response_interface;

    class postaction implements postaction_interface {
        public function process(response_interface $response) {
            //echo '<pre>'.print_r($response, 1).'</pre>';
        }
    }

