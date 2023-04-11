<?php

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

$query = "SELECT $table.*, CONCAT(subjects.code,' - ',subjects.name) subject_name FROM $table JOIN subjects ON subjects.id = $table.subject_id $where ORDER BY ".$col_order." ".$order[0]['dir'];
$db->query = $query." LIMIT $start,$length";
$data  = $db->exec('all');

$db->query = $query;
$total = $db->exec('exists');

return compact('data','total');