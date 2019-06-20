<?php
    use mvc\object_storage;
    use mvc\config;
    use mvc\router;
    use mvc\response;
    use mvc\exceptions\page_not_found;
    use mvc\exceptions\method_not_found;

    // Class autoloader
    spl_autoload_register(function ($class) {
        $class = str_replace('\\', '/', $class);
        require '../classes/'.$class.'.php';
    });

    $router = new router();

    $router->set_namespace('controller');
    $router->route('/test', 'GET', ['test', 'hello']);
    $router->route('/test/(\d+)', 'GET', ['test', 'number']);
    $router->route('/view', 'GET', ['test', 'view']);
    $router->route('/nocontroller', 'GET', ['testx', 'number']);
    $router->route('/nomethod', 'GET', ['test', 'noexist']);
    $router->route('/phpinfo', 'GET', ['test', 'php_info']);
    $router->route('/dump/server', 'GET', ['test', 'dump_server']);

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

