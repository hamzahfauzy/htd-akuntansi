<?php

$where = empty($where) ? 'WHERE ' : $where . ' AND ';
$where .= ' report_id='.(activeMaster()?activeMaster()->id:0);

$db->query = "SELECT * FROM $table $where ORDER BY ".$col_order." ".$order[0]['dir']." LIMIT $start,$length";
$data  = $db->exec('all');

$total = $db->exists($table,$where,[
    $col_order => $order[0]['dir']
]);

$data = array_map(function($d) use ($db){
    $src = $db->single('accounts',['id'=>$d->account_source]);
    $dst = $db->single('accounts',['id'=>$d->account_target]);
    $d->account_source = $src->code . ' - ' . $src->name;
    $d->account_target = $dst->code . ' - ' . $dst->name;

    return $d;
}, $data);

return compact('data','total');