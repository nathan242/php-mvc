<?php
    namespace framework\mvc\interfaces\web_handler;

    use framework\mvc\interfaces\request_interface;

    interface preroute_interface {
        public function process(request_interface $request);
    }
