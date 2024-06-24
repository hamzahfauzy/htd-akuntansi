<?php

$group = $_GET['searchByGroup'];

$report_id = activeMaster()?activeMaster()->id:0;
// SELECT * FROM subjects WHERE user_id IN (SELECT user_id FROM subject_groups WHERE report_id = $report_id) AND 
// $db->query = "SELECT * FROM $table $where ORDER BY ".$col_order." ".$order[0]['dir']." LIMIT $start,$length";
$where = empty($where) ? 'WHERE ' : $where . ' AND ';
$where .= "user_id IN (SELECT user_id FROM subject_groups WHERE report_id = $report_id)";

if($group)
{
    $where .= " AND (SELECT COUNT(*) FROM subject_groups WHERE user_id = subjects.user_id AND group_id = $group) > 0";
}

$query = "SELECT *, (SELECT groups.name FROM `groups` WHERE groups.id = (SELECT group_id FROM subject_groups WHERE user_id = subjects.user_id AND report_id = $report_id)) as group_name FROM subjects $where ORDER BY ".$col_order." ".$order[0]['dir'];
$db->query = $query ." LIMIT $start,$length";
$data  = $db->exec('all');

$db->query = $query;
$total = $db->exec('exists');

return compact('data','total');