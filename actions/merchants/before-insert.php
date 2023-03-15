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
$_POST[$table]['report_id'] = $report->id;