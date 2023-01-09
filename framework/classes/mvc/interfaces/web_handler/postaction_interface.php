<?php
    namespace framework\mvc\interfaces\web_handler;

    use framework\mvc\interfaces\response_interface;

    interface postaction_interface {
        public function process(response_interface $response);
    }
