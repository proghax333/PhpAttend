<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use AttendanceApp\AttendanceWebsocketServer;

require dirname(__FILE__) . '/vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new AttendanceWebsocketServer()
        )
    ),
    9090
);

$server->run();
