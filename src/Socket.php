<?php

namespace Yaro\Socket;


class Socket
{

    public function init($command, $config)
    {
        $WebsocketServer = new Websocket\WebsocketServer($config);
        call_user_func(array($WebsocketServer, $command));
    } // end init

}
