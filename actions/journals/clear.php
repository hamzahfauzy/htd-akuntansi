<?php

$conn = conn();
$db   = new Database($conn);

$db->delete('journals',[
    'report_id' => activeMaster()?activeMaster()->id:0
]);

set_flash_msg(['success'=>'Jurnal berhasil di clear']);
header('location:'.routeTo('journals/index'));