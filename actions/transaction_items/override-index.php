<?php

$transaction_id = $_GET['transaction_id'];
$where = empty($where) ? 'WHERE ' : $where . ' AND ';

$where .= 'transaction_id = '.$transaction_id;

$db->query = "SELECT * FROM $table $where ORDER BY ".$col_order." ".$order[0]['dir']." LIMIT $start,$length";
$data  = $db->exec('all');

$total = $db->exists($table,$where,[
    $col_order => $order[0]['dir']
]);

return compact('data','total');