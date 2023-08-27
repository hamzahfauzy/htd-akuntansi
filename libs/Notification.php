<?php

class Notification
{
    function __construct($event, $payload = [])
    {
        $prefix = '../config/notifications/';
        $event = str_replace('.','/',$event) .'.php';
        if(file_exists($prefix.$event))
        {
            try {
                $event    = require $prefix.$event;
                $target   = $payload[$event['target']];
                $message  = $event['message'];
                $provider = $event['provider'];

                foreach($payload as $key => $value)
                {
                    $message = str_replace('{'.$key.'}', $value, $message);
                }

                //code...
                (new $provider)->setMessage($target, $message);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    }
}