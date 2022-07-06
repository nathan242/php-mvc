<?php
    namespace mvc;

    use mvc\config;
    use mvc\container;
    use mvc\interfaces\response_interface;
    use mvc\exceptions\response_exception;
    use mvc\exceptions\page_not_found;
    use mvc\exceptions\method_not_found;
    use mvc\exceptions\controller_not_found;
    use mvc\exceptions\command_not_found;
    use mvc\exceptions\command_method_not_found;
    use mvc\exceptions\command_controller_not_found;

    class application {
        protected $local_config;
        protected $config;
        protected $container;

        public function init($root_path, $local_config = []) {
            $local_config['root_path'] = $root_path;
            $this->local_config = $local_config;
            $this->config = $this->get_config_instance();
            $this->container = $this->get_container_instance();
            $this->container->set(config::class, $this->config);
            $this->container->set(application::class, $this);
            $this->container->set(container::class, $this->container);

            return $this;
        }

        protected function get_config_instance() {
            if (array_key_exists('config_instance', $this->local_config)) return $this->local_config['config_instance'];

            $config_path = $this->local_config['config_path'] ?? '/config';
            $class = $local_config['config_class'] ?? config::class;
            return new $class("{$this->local_config['root_path']}{$config_path}", $this->local_config);
        }

        protected function get_container_instance() {
            if (array_key_exists('container_instance', $this->local_config)) return $this->local_config['container_instance'];

            $class = $this->local_config['container_class'] ?? container::class;
            return new $class($this->config->get('container'));
        }

        public function get_config() {
            return $this->config;
        }

        public function get_container() {
            return $this->container;
        }

        public function run_cli($arg = []) {
            try {
                return $this->container->get('command')->process($arg);
            } catch (command_not_found $e) {
                echo "Command not found.\n";
                return 1;
            } catch (command_method_not_found $e) {
                echo "Command method not found.\n";
                return 2;
            } catch (command_controller_not_found $e) {
                echo "Command controller not found.\n";
                return 2;
            }
        }

        public function run_web($request = null, $return_response = false) {
            try {
                if ($request === null) {
                    $request = $this->container->get('request');
                    $request->get();
                }

                $response = $this->container->get('router')->process($request);
            } catch (response_exception $e) {
                $response = $e->get_response();
            } catch (page_not_found $e) {
                $response = $this->container->get('response')->set(404, 'Page not found');
            } catch (method_not_found $e) {
                $response = $this->container->get('response')->set(500, 'Internal error');
            } catch (controller_not_found $e) {
                $response = $this->container->get('response')->set(500, 'Internal error');
            }

            if ($return_response) {
                return $response;
            } elseif ($response instanceof response_interface) {
                $response->send();
            } elseif (is_string($response)) {
                echo $response;
            }
        }
    }

