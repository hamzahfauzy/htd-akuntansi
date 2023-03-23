<?php

$report_id = activeMaster()?activeMaster()->id:0;

$db->update('subject_groups',[
    'group_id' => $_POST['group_id'],
],[
    'user_id' => $edit->user_id,
    'report_id' => $report_id
]);