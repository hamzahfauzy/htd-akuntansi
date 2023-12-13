<?php

$conn = conn();
$db   = new Database($conn);

$db->update('transactions', [
    'status' => 'CONFIRM'
], [
    'id' => $_GET['id']
]);

set_flash_msg(['success'=>'Transaksi berhasil di konfirmasi']);
header('location:'.routeTo('crud/index',['table'=>'transactions']));
die();