<?php
    use mvc\container;
    use mvc\config;
    use db\db_factory;
    use mvc\interfaces\response_interface;
    use mvc\exceptions\response_exception;
    use mvc\exceptions\page_not_found;
    use mvc\exceptions\method_not_found;
    use mvc\exceptions\controller_not_found;
    use mvc\exceptions\command_not_found;
    use mvc\exceptions\command_method_not_found;
    use mvc\exceptions\command_controller_not_found;

    $root_path = __DIR__.'/..';

    require_once "{$root_path}/include/autoloader.php";

    $config = new config("{$root_path}/config", ['root_path' => $root_path]);
    $container = new container($config->get('container'));
    $container->set(config::class, $config);

    if ('cli' === php_sapi_name()) {
        try {
            exit($container->get('command')->process($argv));
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
            $request = $container->get('request');
            $request->get();

            $response = $container->get('router')->process($request);
            if ($response instanceof response_interface) {
                $response->send();
                exit();
            } elseif (is_string($response)) {
                exit($response);
            }
        } catch (response_exception $e) {
            $e->get_response()->send();
            exit();
        } catch (page_not_found $e) {
            $container->get('response')->set(404, 'Page not found')->send();
            exit();
        } catch (method_not_found $e) {
            $container->get('response')->set(500, 'Internal error')->send();
            exit();
        } catch (controller_not_found $e) {
            $container->get('response')->set(500, 'Internal error')->send();
            exit();
        }
    }

