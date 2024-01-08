<?php

$where = empty($where) ? 'WHERE ' : $where . ' AND ';
$where .= ' report_id='.(activeMaster()?activeMaster()->id:0);

$db->query = "SELECT *, (SELECT COUNT(*) FROM subject_groups WHERE subject_groups.group_id = groups.id) as jumlah_siswa FROM `$table` $where ORDER BY ".$col_order." ".$order[0]['dir']." LIMIT $start,$length";
$data  = $db->exec('all');

$total = $db->exists($table,$where,[
    $col_order => $order[0]['dir']
]);

return compact('data','total');