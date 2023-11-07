<?php
return [
    'default' => ['\Framework\Command\DefaultCommand', 'listCommands'],
    'commands' => [
        'info' => ['Client', 'info', 'Get information from WSDL'],
        'call' => ['Client', 'call', 'Call SOAP API']
    ]
];
