<?php

$report_id = activeMaster()->id;
$_POST[$table]['report_id'] = $report_id;
$_POST[$table]['account_source'] = $db->single('accounts',['report_id' => $report_id, 'code'=>$_POST[$table]['account_source']])->id;
$_POST[$table]['account_target'] = $db->single('accounts',['report_id' => $report_id, 'code'=>$_POST[$table]['account_target']])->id;