<?php
    use mvc\object_storage;
    use mvc\config;
    use mvc\router;
    use mvc\response;
    use mvc\exceptions\page_not_found;
    use mvc\exceptions\method_not_found;

    define('ROOT_PATH', __DIR__.'/..');

    // Class autoloader
    spl_autoload_register(function ($class) {
        $class = str_replace('\\', '/', $class);
        require ROOT_PATH.'/classes/'.$class.'.php';
    });

    $config = new config(ROOT_PATH.'/config');
    $router = new router($config->get('router'));

    object_storage::add('config', $config);
    object_storage::add('router', $router);

    try {
        $response = $router->process();
        if ($response instanceof response) {
            $response->send();
            exit();
        } elseif (is_string($response)) {
            exit($response);
        }
    } catch (page_not_found $e) {
        response::set(404, 'Page not found')->send();
        exit();
    } catch (method_not_found $e) {
        response::set(500, 'Internal error')->send();
        exit();
    }

