<?php

$conn = conn();
$db   = new Database($conn);

$data = $db->single('application');
$success_msg = get_flash_msg('success');

if(request() == 'POST')
{
    if(isset($_FILES['logo']) && !empty($_FILES['logo']['name']))
    {
        $_POST['app']['logo'] = do_upload($_FILES['logo'],'uploads');
    }

    $db->update('application',$_POST['app'],[
        'id' => $data->id
    ]);

    set_flash_msg(['success'=>'Detail Aplikasi berhasil diupdate']);
    header('location:'.routeTo('application/index'));
    die();
}

return compact('data','success_msg');