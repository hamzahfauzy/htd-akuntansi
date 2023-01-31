<?php

$report_id = activeMaster()->id;
$_POST[$table]['report_id'] = $report_id;
$_POST[$table]['account_id'] = $db->single('accounts',['report_id' => $report_id,'code'=>$_POST[$table]['account_id']])->id;