<?php

// bill validation
Validation::run([
    'bill_code' => [
        'required','exists:bills,bill_code,'.$_POST['bill_code']
    ],
    'amount' => [
        'required','min:1'
    ],
    'description' => [
        'required'
    ]
],$_POST,'json');

$conn = conn();
$db   = new Database($conn);

$transaction_code = 'TRX-'.strtotime('now');
$bill = $db->single('bills',['code' => $_POST['bill_code']]);
$merchant = $db->single('merchants',['id' => $bill->merchant_id]);
$subject = $db->single('subjects',['id' => $bill->subject_id]);

$transaction = $db->insert('transactions',[
    'subject_id' => $subject->id,
    'report_id'  => $bill->report_id,
    'transaction_code' => $transaction_code,
    'total' => $_POST['amount']
]);

$insert = $db->insert('transaction_items',[
    'transaction_id' => $transaction->id,
    'bill_id' => $bill->id,
    'amount' => $_POST['amount'],
    'description' => $description,
    'merchant_id' => $bill->merchant_id
]);

if($merchant->debt_account_id)
{
    // jurnal debet
    $db->insert('journals',[
        'report_id'  => $bill->report_id,
        'account_id' => $merchant->debt_account_id,
        'transaction_type' => 'Debit',
        'amount' => $insert->amount,
        'description' => $insert->description,
        'transaction_code' => 'transaction-'.$transaction->transaction_code,
        'date' => date('Y-m-d')
    ]);
}

if($merchant->credit_account_id)
{
    // jurnal kredit
    $db->insert('journals',[
        'report_id'  => $bill->report_id,
        'account_id' => $merchant->credit_account_id,
        'transaction_type' => 'Kredit',
        'amount' => $insert->amount,
        'description' => $insert->description,
        'transaction_code' => 'transaction-'.$transaction->transaction_code,
        'date' => date('Y-m-d')
    ]);
}

die();