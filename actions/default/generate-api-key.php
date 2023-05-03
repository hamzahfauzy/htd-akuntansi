<?php

if(request() == 'POST')
{
    $conn = conn();
    $db   = new Database($conn);

    $db->update('users',[
        'auth_token' => generateRandomString()
    ],[
        'id' => auth()->user->id
    ]);

    set_flash_msg(['success'=>'API Key berhasil di generate']);
    header('location:'.routeTo('default/profile'));
    die();

}