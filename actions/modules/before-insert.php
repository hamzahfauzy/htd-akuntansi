<?php

$code = $_POST[$table]['account_id'];
$account = $db->single('accounts',['code'=>$code,'report_id'=>activeMaster()->id]);
$_POST[$table]['report_id'] = activeMaster()->id;
$_POST[$table]['account_id'] = $account->id;