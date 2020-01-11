<?php
    use mvc\container;
    use mvc\config;
    use mvc\router;
    use mvc\command;
    use mvc\session;
    use db\db_factory;
    use mvc\request;
    use mvc\response;
    use mvc\exceptions\response_exception;
    use mvc\exceptions\page_not_found;
    use mvc\exceptions\method_not_found;
    use mvc\exceptions\controller_not_found;
    use mvc\exceptions\command_not_found;
    use mvc\exceptions\command_method_not_found;
    use mvc\exceptions\command_controller_not_found;

    define('ROOT_PATH', __DIR__.'/..');

    require_once ROOT_PATH.'/include/autoloader.php';

    $container = new container();
    $request = new request();
    $response = new response();
    $config = new config(ROOT_PATH.'/config');
    $router = new router($container, $config->get('router'));
    $command = new command($container, $config->get('commands'), $config->get('application'));
    $session = new session($config->get('application')['name']);
    $db = db_factory::get($config);

    $container->add('request', $request);
    $container->add('response', $response);
    $container->add('config', $config);
    $container->add('router', $router);
    $container->add('command', $command);
    $container->add('session', $session);
    $container->add('db', $db);

    if ('cli' === php_sapi_name()) {
        try {
            exit($command->process($argv));
        } catch (command_not_found $e) {
            echo "Command not found.\n";
            exit(1);
        } catch (command_method_not_found $e) {
            echo "Command method not found.\n";
            exit(2);
        } catch (command_controller_not_found $e) {
            echo "Command controller not found.\n";
            exit(2);
        }
    } else {
        try {
            $request->get();

            $response = $router->process($request);
            if ($response instanceof response) {
                $response->send();
                exit();
            } elseif (is_string($response)) {
                exit($response);
            }
        } catch (response_exception $e) {
            $e->get_response()->send();
            exit();
        } catch (page_not_found $e) {
            $response->set(404, 'Page not found')->send();
            exit();
        } catch (method_not_found $e) {
            $response->set(500, 'Internal error')->send();
            exit();
        } catch (controller_not_found $e) {
            $response->set(500, 'Internal error')->send();
            exit();
        }
    }

