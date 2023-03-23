<?php

$debt_account = $db->single('accounts',[
    'id' => $data->debt_account_id
]);

$credit_account = $db->single('accounts',[
    'id' => $data->credit_account_id
]);



$data->debt_account_id = $debt_account->code;
$data->credit_account_id = $credit_account->code;

if($data->debt_bill_account_id)
{
    $debt_bill_account = $db->single('accounts',[
        'id' => $data->debt_bill_account_id
    ]);
    $data->debt_bill_account_id = $debt_bill_account->code;
}

if($data->credit_bill_account_id)
{
    $credit_bill_account = $db->single('accounts',[
        'id' => $data->credit_bill_account_id
    ]);
    
    $data->credit_bill_account_id = $credit_bill_account->code;
}

return $data;