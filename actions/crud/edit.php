<?php

$table = $_GET['table'];
Page::set_title('Edit '._ucwords(__($table)));
$conn = conn();
$db   = new Database($conn);
$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');
$fields = config('fields')[$table];

$data = $db->single($table,[
    'id' => $_GET['id']
]);

if(file_exists('../actions/'.$table.'/override-edit-fields.php'))
    $fields = require '../actions/'.$table.'/override-edit-fields.php';

if(file_exists('../actions/'.$table.'/override-edit-data.php'))
{
    $data = require '../actions/'.$table.'/override-edit-data.php';
}

if(request() == 'POST')
{
    if(file_exists('../actions/'.$table.'/before-edit.php'))
        require '../actions/'.$table.'/before-edit.php';

    $edit = $db->update($table,$_POST[$table],[
        'id' => $_GET['id']
    ]);

    if(file_exists('../actions/'.$table.'/after-edit.php'))
        require '../actions/'.$table.'/after-edit.php';

    set_flash_msg(['success'=>_ucwords(__($table)).' berhasil diupdate']);
    header('location:'.routeTo('crud/index',['table'=>$table]));
}

return [
    'data' => $data,
    'error_msg' => $error_msg,
    'old' => $old,
    'table' => $table,
    'fields' => $fields
];
