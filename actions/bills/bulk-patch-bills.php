<?php

$bills_file = $_GET['bills_file'];
if(file_exists($bills_file))
{
    $bill_code = file($bills_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $bill_code = implode("','", $bill_code);
    $conn  = conn();
    $db    = new Database($conn);

    $db->query = "SELECT * FROM bills WHERE bill_code IN ('$bill_code') AND remaining_payment > 0 AND status = 'BELUM LUNAS'";
    $bills     = $db->exec('all');

    foreach($bills as $bill)
    {
        $merchant = $db->single('merchants',['id' => $bill->merchant_id]);
        $subject  = $db->single('subjects',['id' => $bill->subject_id]);

        $amount      = $bill->remaining_payment;
        $description = "Pembayaran ".str_replace('Tagihan ','',$bill->description);
        $transaction_code = 'TRX-'.strtotime('now');

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

        echo "Tagihan $bill->bill_code Berhasil<br>";
    }

    $db->query = "UPDATE bills SET status = 'LUNAS' WHERE bill_code IN ('$bill_code') AND remaining_payment > 0 AND status = 'BELUM LUNAS'";
    $db->exec();
}