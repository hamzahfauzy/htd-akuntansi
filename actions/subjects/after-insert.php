<?php

$report_id = activeMaster()?activeMaster()->id:0;

$db->insert('subject_groups',[
    'user_id' => $insert->user_id,
    'group_id' => $_POST['group_id'],
    'report_id' => $report_id
]);