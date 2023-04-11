<?php
$report_id = activeMaster() ? activeMaster()->id : 0;
$merchants = $db->all('merchants',[
    'report_id' => $report_id
]);

$groups = $db->all('groups',[
    'report_id' => $report_id
]);

$subjects = $db->all('subjects');

return compact('merchants','subjects','groups');