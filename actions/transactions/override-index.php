<?php

$date = json_decode($_GET['searchByDate']);

$where = "";

if(!empty($search))
{
    $_where = [];
    foreach($search_columns as $col)
    {
        $_where[] = "$table.$col LIKE '%$search%'";
    }

    $where = "WHERE (".implode(' OR ',$_where)." OR subjects.name LIKE '%$search%' OR subjects.code LIKE '%$search%')";
}

$where = empty($where) ? 'WHERE ' : $where . ' AND ';
$where .= ' report_id='.(activeMaster()?activeMaster()->id:0);

if($date->start_at || $date->end_at)
{
    $date->start_at = $date->start_at ?? date('Y-m-d');
    $date->end_at = $date->end_at ?? date('Y-m-d');

    $where .= " AND DATE_FORMAT(transactions.created_at, '%Y-%m-%d') BETWEEN '$date->start_at' AND '$date->end_at'";
}

$query = "SELECT $table.*, CONCAT(subjects.code,' - ',subjects.name) subject_name FROM $table JOIN subjects ON subjects.id = $table.subject_id $where ORDER BY ".$col_order." ".$order[0]['dir'];
$db->query = $query." LIMIT $start,$length";
$data  = $db->exec('all');

$db->query = $query;
$total = $db->exec('exists');

return compact('data','total');