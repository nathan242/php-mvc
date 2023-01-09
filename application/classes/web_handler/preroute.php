<?php
    namespace application\web_handler;

    use framework\mvc\interfaces\web_handler\preroute_interface;
    use framework\mvc\interfaces\request_interface;

    class preroute implements preroute_interface {
        public function process(request_interface $request) {
            //$request->params['COOKIE']['inserted'] = 'Cookie inserted into request via preroute';
        }
    }

