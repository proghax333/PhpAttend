<?php

namespace AttendanceApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use AttendanceApp\JWTAuth;
use AttendanceApp\UserSession;

class AttendanceWebsocketServer implements MessageComponentInterface
{
    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        echo "Started Server!\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $current_timestamp = time();
        $message_data = json_decode($msg);

        if ($message_data && $message_data->type === 'authentication') {
            $token = $message_data->token;
            $data = $this->clients->offsetGet($from);

            if (isset($data->offset)) {
                $from->send("8");
            } else if (JWTAuth::is_valid_token($token)) {
                $status = new UserSession($current_timestamp);
                // Set data
                $this->clients->offsetSet($from, $status);
                // Send success message to client
                $from->send("1");
            } else {
                // Send auth failure
                $from->send("0");
            }
        } else {
            $data = $this->clients->offsetGet($from);
            if ($data) {
                $data->count = $current_timestamp - $data->creation_timestamp;
                $data->last_timestamp = $current_timestamp;

                $this->clients->offsetSet($data);

                // Send attendance recorded
                $from->send("2");
            } else {
                // Send attendance not recorded.
                $from->send("3");
            }
        }
        /*
    foreach ( $this->clients as $client ) {
      if ( $from->resourceId == $client->resourceId ) {
        continue;
      }
      $client->send( "Client $from->resourceId said $msg" );
    }*/
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }
}
