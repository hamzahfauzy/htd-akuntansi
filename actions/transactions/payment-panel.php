<?php

Page::set_title('Panel Transaksi');

if(request() == 'POST')
{
    $conn = conn();
    $db   = new Database($conn);

    $report_id = activeMaster()?activeMaster()->id:0;
    $total = array_sum($_POST['amount']);

    // insert to transaction
    $transaction = $db->insert('transactions',[
        'subject_id' => $_POST['subject_id'],
        'report_id'  => $report_id,
        'transaction_code' => $_POST['transaction_code'],
        'total' => $total
    ]);

    foreach($_POST['bill_id'] as $key => $bill_id)
    {
        $bill = $db->single('bills',['id' => $bill_id]);

        // insert to transcation items
        $insert = $db->insert('transaction_items',[
            'transaction_id' => $transaction->id,
            'bill_id' => $bill_id,
            'amount'  => $_POST['amount'][$key],
            'description' => $_POST['description'][$key],
            'merchant_id' => $bill->merchant_id
        ]);

        // update bill payment
        $sisa_pembayaran = $bill->remaining_payment-$insert->amount;
        $status = $sisa_pembayaran == 0 ? 'LUNAS' : 'BELUM LUNAS';
        $bill = $db->update('bills',[
            'remaining_payment' => $sisa_pembayaran,
            'status' => $status
        ],[
            'id' => $bill_id
        ]);

        // merchant payment event
        $merchant = $db->single('merchants',['id' => $bill->merchant_id]);
        if($merchant->debt_account_id)
        {
            // jurnal debet
            $db->insert('journals',[
                'report_id'  => $report_id,
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
                'report_id'  => $report_id,
                'account_id' => $merchant->credit_account_id,
                'transaction_type' => 'Kredit',
                'amount' => $insert->amount,
                'description' => $insert->description,
                'transaction_code' => 'transaction-'.$transaction->transaction_code,
                'date' => date('Y-m-d')
            ]);
        }

    }

    // payment notif here

    set_flash_msg(['success'=>'Transaksi berhasil disimpan']);
    header('location:'.routeTo('crud/index',['table'=>'transactions']));
}