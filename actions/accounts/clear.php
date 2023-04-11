<?php

$table = 'accounts';
$conn = conn();
$db   = new Database($conn);

$report_id = activeMaster() ? activeMaster()->id : 0;

$db->delete($table,[
    'report_id' => $report_id
]);

set_flash_msg(['success'=>_ucwords(__($table)).' berhasil dihapus']);
header('location:'.routeTo('accounts/index'));
die();
