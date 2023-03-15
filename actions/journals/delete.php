<?php

$table = 'journals';
$conn = conn();
$db   = new Database($conn);

$data = $db->single($table, [
    'id' => $_GET['id']
]);

$db->delete($table,[
    'id' => $_GET['id']
]);

set_flash_msg(['success'=>_ucwords(__($table)).' berhasil dihapus']);
header('location:'.routeTo('crud/index',['table'=>$table]));
die();
