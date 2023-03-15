<?php

$debt_account = $db->single('accounts',[
    'id' => $data->debt_account_id
]);

$credit_account = $db->single('accounts',[
    'id' => $data->credit_account_id
]);

$data->debt_account_id = $debt_account->code;
$data->credit_account_id = $credit_account->code;

return $data;