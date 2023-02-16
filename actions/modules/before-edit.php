<?php

$code = $_POST[$table]['account_id'];
$account = $db->single('accounts',['code'=>$code]);
$_POST[$table]['account_id'] = $account->id;