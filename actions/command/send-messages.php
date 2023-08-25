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

echo "Counting Start\n";

$conn = conn();
$db   = new Database($conn);

$messages = $db->all('messages',[
    'status' => 'PENDING'
]);
foreach($messages as $message)
{
    echo "send message to $message->target Started\n";
    if($message->send_by == 'Whatsapp')
    {
        Whatsapp::send($message->target, $message->content);
    }
    $db->update('messages',['status' => 'DONE'],[
        'id' => $message->id
    ]);
    echo "send message to $message->target Finished\n";
}

echo "Counting Finish\n";

unlink($parent_path . 'lock.txt');

die();