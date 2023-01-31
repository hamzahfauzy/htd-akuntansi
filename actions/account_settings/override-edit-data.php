<?php

$data->account_source = $db->single('accounts',['id'=>$data->account_source])->code;
$data->account_target = $db->single('accounts',['id'=>$data->account_target])->code;

return $data;