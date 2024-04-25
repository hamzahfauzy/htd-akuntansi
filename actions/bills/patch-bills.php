<?php

$table = 'bills';

$inputFileName = 'public/bills.xlsx';

/**  Identify the type of $inputFileName  **/
$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
/**  Create a new Reader of the type that has been identified  **/
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
/**  Load $inputFileName to a Spreadsheet Object  **/
$spreadsheet = $reader->load($inputFileName);
$worksheet   = $spreadsheet->getActiveSheet();
$highestRow  = $worksheet->getHighestRow();
$highestColumn = $worksheet->getHighestColumn();
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
    
//inilah looping untuk membaca cell dalam file excel,perkolom
$success = 0;
$failed  = 0;
$report_id = activeMaster()->id;

$conn = conn();
$db   = new Database($conn);
$log = '';

for ($row = 2; $row <= $highestRow; $row++) { 
    $bill_code = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
    $amount = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
    $email = $worksheet->getCellByColumnAndRow(5, $row)->getValue();

    $bill     = $db->single('bills',[
        'bill_code' => $bill_code,
        'amount' => $amount,
    ], [
        'remaining_payment' => 'DESC'
    ]);

    if(!$bill)
    {
        $merchant = strpos($bill_code, 'spp') !== -1 ? 'SPP' : 'Daftar Ulang';
        $description = "Tagihan $merchant";
        $bill = createBills($db, $report_id, $email, 'email', $bill_code, $amount, $merchant, $description);
    }

    if($bill->status == 'LUNAS')
    {
        $l = "Bill $bill_code sudah lunas\n";
        $log .= $l;
        echo $l;
        continue;
    }

    $merchant = $db->single('merchants',['id' => $bill->merchant_id]);
    $subject  = $db->single('subjects',['id' => $bill->subject_id]);

    $description = "Pembayaran ".str_replace('Tagihan ','',$bill->description);
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
        $l = "payment for $bill_code fail because it is invalid (remaining_payment : $bill->remaining_payment, amount : $amount)\n";
        // $log .= $l;
        echo $l;
        continue;
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
        'user_id' => 1
    ]);

    $insert = $db->insert('transaction_items',[
        'transaction_id' => $transaction->id,
        'bill_id' => $bill->id,
        'amount' => $amount,
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

    $l = "Payment for $bill_code success\n";
    $log .= $l;
    echo $l;
}

file_put_contents('public/payment_log.txt', $log);