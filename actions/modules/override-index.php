<?php

$where = empty($where) ? 'WHERE ' : $where . ' AND ';
$where .= ' '.$table.'.report_id='.(activeMaster()?activeMaster()->id:0);

$db->query = "SELECT $table.*, CONCAT(accounts.code,' - ',accounts.name) as account_id FROM $table JOIN accounts ON accounts.id=$table.account_id $where ORDER BY ".$col_order." ".$order[0]['dir']." LIMIT $start,$length";
$data  = $db->exec('all');

$total = $db->exists($table,$where,[
    $col_order => $order[0]['dir']
]);

return compact('data','total');