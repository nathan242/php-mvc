<?php

namespace Framework\Mvc\Traits;

trait SocketErrorTrait
{
    protected function getSocketErrorString($socket): string
    {
        $code = socket_last_error($socket);
        return $code.': '.socket_strerror($code);
    }
}

