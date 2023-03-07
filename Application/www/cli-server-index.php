<?php
if ('cli-server' === php_sapi_name()) {
    if (file_exists(__DIR__ . '/' . $_SERVER['REQUEST_URI'])) {
        return false;
    }
}

require 'index.php';

