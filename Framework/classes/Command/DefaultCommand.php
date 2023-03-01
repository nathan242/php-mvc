<?php

namespace Framework\Command;

/**
 * Default command to run when no command specified
 *
 * Displays a list of supported commands with descriptions
 *
 * @package Framework\Command
 */
class DefaultCommand extends BaseCommand
{
    /**
     * List supported commands with descriptions
     *
     * @param array<string> $args Command arguments
     * @return int Return code
     */
    public function listCommands(array $args = []): int
    {
        $appConfig = $this->config->get('application');
        $appName = $appConfig['name'] ?? '<not configured>';
        $appVer = $appConfig['version'] ?? '<not configured>';

        $commandsConfig = $this->config->get('commands');
        $commands = $commandsConfig['commands'] ?? [];

        echo "{$appName} [{$appVer}]\n\n";
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
