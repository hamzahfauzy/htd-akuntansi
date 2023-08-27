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

$bill     = $db->single('bills',['bill_code' => $_POST['bill_code']]);
$merchant = $db->single('merchants',['id' => $bill->merchant_id]);
$subject  = $db->single('subjects',['id' => $bill->subject_id]);

$amount      = $_POST['amount'];
$description = $_POST['description'];
$sisa        = $bill->remaining_payment - $amount;
$transaction_code = 'TRX-'.strtotime('now');

$payload = [
    'amount'           => $amount,
    'description'      => $description,
    'bill_code'        => $bill->bill_code,
    'bill_amount'      => number_format($bill->amount),
    'bill_description' => $bill->description,
    'transaction_code' => $transaction_code,
    'merchant_name'    => $merchant->name,
    'subject_name'     => $subject->name,
    'subject_phone'    => $subject->phone,
    'subject_email'    => $subject->email,
];

if($sisa < 0)
{
    http_response_code(403);
    echo json_encode([
        'success' => false,
        'message' => 'payment for '.$_POST['bill_code'].' fail because it is invalid'
    ]);

    new Notification('fail', $payload);

    die();
    
}
$db->update('bills',[
    'remaining_payment' => $sisa,
    'status' => $sisa == 0 ? 'LUNAS' : 'BELUM LUNAS'
],[
    'id' => $bill->id
]);

$transaction = $db->insert('transactions',[
    'subject_id' => $subject->id,
    'report_id'  => $bill->report_id,
    'transaction_code' => $transaction_code,
    'total' => $amount,
    'user_id' => auth('api')->user->id
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

new Notification('success', $payload);

echo json_encode([
    'success' => true,
    'message' => 'payment for '.$_POST['bill_code'].' success'
]);
die();
