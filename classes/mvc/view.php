<?php
    namespace mvc;
    
    use mvc\interfaces\response_content;
    
    class view implements response_content {
        private $view_variables;
        private $view_view;
        
        public function set_view($view, $variables = []) {
            $this->view_view = $view;
            $this->view_variables = $variables;
        }
        
        public function output_content() {
            foreach ($this->view_variables as $key => $value) {
                if (!isset($$key)) {
                    $$key = $value;
                }
            }
            require '../view/'.$this->view_view;
        }
        
        public static function set($view, $variables = []) {
            $view_obj = new self();
            $view_obj->set_view($view, $variables);
            return $view_obj;
        }
        
        public static function render($view, $variables = []) {
            self::set($view, $variables)->output_content();
        }
        
        public function __get($name) {
            if (array_key_exists($name, $this->view_variables)) {
                return $this->view_variables[$name];
            }
            
            return null;
        }
    }