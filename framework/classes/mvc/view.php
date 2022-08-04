<?php
    namespace framework\mvc;
    
    use framework\mvc\interfaces\response_content;
    use RuntimeException;
    
    class view implements response_content {
        protected $view_path;
        protected $view_variables;
        protected $view_view;

        public function __construct($config = []) {
            if (array_key_exists('path', $config)) {
                $this->view_path = $config['path'];
            }
        }
        
        public function set_view($view, $variables = []) {
            $this->view_view = $view;
            $this->view_variables = $variables;
        }

        public function variables($variables = []) {
            $this->view_variables = array_merge($this->view_variables, $variables);
        }

        public function sub_view($view, $variables, $name) {
            $this->variables([$name => self::set(['path' => $this->view_path], $view, $variables)]);
        }

        public function get($view, $variables = [], $name = 'view') {
            $this->sub_view($view, $variables, $name);
            return $this;
        }
        
        public function output_content() {
            foreach ($this->view_variables as $key => $value) {
                if (!isset($$key)) {
                    if ($value instanceof response_content) {
                        ob_start();
                        $value->output_content();
                        $$key = ob_get_clean();
                    } else {
                        $$key = $value;
                    }
                }
            }

            if (null === $this->view_path) {
                throw new RuntimeException('View path is not configured');
            }

            require $this->view_path.$this->view_view;
        }

        public static function set($config, $view, $variables = []) {
            $view_obj = new self($config);
            $view_obj->set_view($view, $variables);
            return $view_obj;
        }

        public static function render($config, $view, $variables = []) {
            self::set($config, $view, $variables)->output_content();
        }

        public function __get($name) {
            if (array_key_exists($name, $this->view_variables)) {
                return $this->view_variables[$name];
            }

            return null;
        }

        public function __set($name, $value) {
            $this->view_variables[$name] = $value;
        }

        public function __isset($name) {
            return array_key_exists($name, $this->view_variables);
        }
    }
