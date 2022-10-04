<?php
    namespace application\controller;

    use framework\controller\base_controller;

    class api extends base_controller {
        public function post_test() {
            return $this->response->set(200, 'DATA: '.$this->request->body);
        }

        public function api_test() {
            $ret = [
                'method' => $this->request->method,
                'body' => $this->request->body
            ];

            return $this->response->set(200, json_encode($ret));
        }
    }

