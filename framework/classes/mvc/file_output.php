<?php
    namespace framework\mvc;
    
    use framework\mvc\interfaces\response_content;

    class file_output implements response_content {
        protected $path;
        protected $handle;
        protected $finfo;

        public function set($path) {
            $this->handle = fopen($path, 'r');

            if ($this->handle === false) {
                // Throw
            }

            $this->path = $path;

            return $this;
        }

        public function get_headers() {
            $headers = [];
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $filename = basename($this->path);

            $headers['Content-type'] = finfo_file($finfo, $this->path);
            $headers['Content-Disposition'] = 'attachment; filename="'.$filename.'"';

            return $headers;
        }

        public function output_content() {
            echo fread($this->handle, filesize($this->path));
        }
    }

