<?php

Validation::run([
    'id' => [
        'required','exists:reports,id,'.$_GET['id']
    ]
], $_GET);

$conn = conn();
$db   = new Database($conn);
$params = [
    'id' => $_GET['id']
];

$data = $db->single('reports',$params);

$db->update('reports',['is_active'=>'TIDAK'],['1'=>1]);
if($data->is_active == 'TIDAK')
{
    $db->update('reports',['is_active'=>'YA'],$params);
}

set_flash_msg(['success'=>'Master data berhasil diupdate']);
header('location:'.routeTo('crud/index',['table'=>'reports']));