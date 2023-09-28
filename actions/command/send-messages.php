<?php

// check if file exists
$parent_path = '';
if (!in_array(php_sapi_name(),["cli","cgi-fcgi"])) {
    $parent_path = 'public/';
}

if(file_exists($parent_path . 'lock.txt'))
{
    die();
}

file_put_contents($parent_path . 'lock.txt', strtotime('now'));

try {
    echo date('Y-m-d H:i:s') . " - Send Message Start\n";

    $conn = conn();
    $db   = new Database($conn);

    $messages = $db->all('messages',[
        'status' => 'PENDING'
    ]);

    foreach($messages as $message)
    {
        $execute_at = date('Y-m-d H:i:s');
        echo $execute_at . " - send message to $message->target Started\n";
        try {
            //code...
            (new $message->send_by)->send($message->target, $message->content);
        } catch (\Throwable $th) {
            //throw $th;
            print_r($th);
        }

        $db->update('messages',[
            'status' => 'DONE',
            'execute_at' => $execute_at,
        ],[
            'id' => $message->id
        ]);
        echo $execute_at . " - send message to $message->target Finished\n";
    }

    echo date('Y-m-d H:i:s') . " - Send Message Finish\n";
} catch (\Throwable $th) {
    //throw $th;
}

unlink($parent_path . 'lock.txt');

die();