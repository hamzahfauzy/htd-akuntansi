<?php

// validation
$report = activeMaster();
$debt_account = $db->single('accounts',[
    'report_id' => $report ? $report->id : 0,
    'code'      => $_POST[$table]['debt_account_id']
]);

if(empty($debt_account))
{
    redirectBack(['error' => 'Akun Debit tidak valid','old' => $_POST[$table]]);
    die();
}

$_POST[$table]['debt_account_id'] = $debt_account->id;

$credit_account = $db->single('accounts',[
    'report_id' => $report ? $report->id : 0,
    'code'      => $_POST[$table]['credit_account_id']
]);

if(empty($credit_account))
{
    redirectBack(['error' => 'Akun Kredit tidak valid','old' => $_POST[$table]]);
    die();
}

$_POST[$table]['credit_account_id'] = $credit_account->id;

if(!empty($_POST[$table]['debt_bill_account_id']))
{
    $debt_bill_account = $db->single('accounts',[
        'report_id' => $report ? $report->id : 0,
        'code'      => $_POST[$table]['debt_bill_account_id']
    ]);
    
    if(empty($debt_bill_account))
    {
        redirectBack(['error' => 'Akun Debit Tagihan tidak valid','old' => $_POST[$table]]);
        die();
    }
    
    $_POST[$table]['debt_bill_account_id'] = $debt_bill_account->id;
}
else
{
    unset($_POST[$table]['debt_bill_account_id']);
}


if(!empty($_POST[$table]['credit_bill_account_id']))
{
    $credit_bill_account = $db->single('accounts',[
        'report_id' => $report ? $report->id : 0,
        'code'      => $_POST[$table]['credit_bill_account_id']
    ]);
    
    if(empty($credit_bill_account))
    {
        redirectBack(['error' => 'Akun Kredit Tagihan tidak valid','old' => $_POST[$table]]);
        die();
    }
    
    $_POST[$table]['credit_bill_account_id'] = $credit_bill_account->id;
}
else
{
    unset($_POST[$table]['credit_bill_account_id']);
}