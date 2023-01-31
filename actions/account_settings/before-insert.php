<?php

$report_id = activeMaster()->id;
$source = $db->single('accounts',['report_id' => $report_id, 'code'=>$_POST[$table]['account_source']])->id;
$targets = explode(',',$_POST[$table]['account_target']);
foreach($targets as $target)
{
    $target = $db->single('accounts',['code'=>$target])->id;
    $db->insert('account_settings',[
        'report_id' => $report_id,
        'account_source' => $source,
        'account_target' => $target,
        'cash_source' => $_POST[$table]['cash_source'],
    ]);
}

set_flash_msg(['success'=>_ucwords(__($table)).' berhasil ditambahkan']);
header('location:'.routeTo('crud/index',['table'=>$table]));

// $_POST[$table]['account_source'] = $source;
// $_POST[$table]['account_target'] = $db->single('accounts',['code'=>$_POST[$table]['account_target']])->id;