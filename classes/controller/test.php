<?php
    namespace controller;

    use mvc\response;
    use mvc\view;
    use gui\gui;

    class test {
        public function hello() {
            return response::set(200, 'Hello World!!!');
        }

        public function number($id) {
            return response::set(200, "$id");
        }

        public function view() {
            ob_start();
            ob_start();
            gui::table([['hello', 'world'], ['test', 'me']]);
            gui::panel('Test', ob_get_clean());
            $table = ob_get_clean();
            return response::set(200, view::set('test.php', ['table' => $table]));
        }
    }
