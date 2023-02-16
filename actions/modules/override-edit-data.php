<?php

$account = $db->single('accounts',['id'=>$data->account_id]);
$data->account_id = $account->code;

return $data;