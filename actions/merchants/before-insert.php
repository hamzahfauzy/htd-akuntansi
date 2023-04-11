<?php

// validation
$report = activeMaster();
if(empty($_POST[$table]['debt_bill_account_id']))
{
    unset($_POST[$table]['debt_bill_account_id']);
}


if(empty($_POST[$table]['credit_bill_account_id']))
{
    unset($_POST[$table]['credit_bill_account_id']);
}

$_POST[$table]['report_id'] = $report->id;