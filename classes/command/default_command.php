<?php
    namespace command;

    class default_command extends base_command {
        public function list_commands() {
            $app_config = $this->config->get('application');
            $app_name = $app_config['name'] ?? '<not configured>';
            $app_ver = $app_config['version'] ?? '<not configured>';

            $commands_config = $this->config->get('commands');
            $commands = $commands_config['commands'] ?? [];

            echo "{$app_name} [{$app_ver}]\n\n";
            echo "Available commands:\n";

            foreach ($commands as $command => $details) {
                echo str_pad("{$command} ", 25);
                if (isset($details[2])) {
                    echo " - {$details[2]}";
                }
                echo "\n";
            }

            echo "\n";

            return 0;
        }
    }
