<?php

$data->account_id = $db->single('accounts',['id'=>$data->account_id])->code;

return $data;