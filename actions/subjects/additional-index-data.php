<?php
$report_id = activeMaster() ? activeMaster()->id : 0;
$groups = $db->all('groups',[
    'report_id' => $report_id
]);

return compact('groups');