<?php

$report_id = activeMaster()->id;

$merchant = $db->single('merchants',[
    'id' => $insert->merchant_id
]);

if($merchant->debt_bill_account_id)
{
    // jurnal debet
    $db->insert('journals',[
        'report_id'  => $report_id,
        'account_id' => $merchant->debt_bill_account_id,
        'transaction_type' => 'Debit',
        'amount' => $insert->amount,
        'description' => $insert->description,
        'transaction_code' => 'bill-'.$insert->bill_code,
        'date' => date('Y-m-d')
    ]);
}

if($merchant->credit_bill_account_id)
{
    // jurnal kredit
    $db->insert('journals',[
        'report_id'  => $report_id,
        'account_id' => $merchant->credit_bill_account_id,
        'transaction_type' => 'Kredit',
        'amount' => $insert->amount,
        'description' => $insert->description,
        'transaction_code' => 'bill-'.$insert->bill_code,
        'date' => date('Y-m-d')
    ]);
}

// bill notif here