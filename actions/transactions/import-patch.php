<?php

$table = 'transactions';
Page::set_title('Import '._ucwords(__($table)));
$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');
$fields = [
    'file' => [
        'label' => 'file',
        'type'  => 'file'
    ]
];

if(request() == 'POST')
{
    Validation::run([
        'file' => [
            'file','required','mime:xls,xlsx'
        ]
    ], array_merge($_FILES));

    $inputFileName = $_FILES['file']['tmp_name'];

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
    $logs = '';

    for ($row = 2; $row <= $highestRow; $row++) { 
        $code = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
        $clause = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
        $transaction_code = 'TRX-'.strtotime('now');
        $amount = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
        $bill_code = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
        $description = $worksheet->getCellByColumnAndRow(5, $row)->getValue();

        // check if subject exists
        if(!$db->exists('subjects',[''.$clause.'' => $code]))
        {
            $failed++;
            $logs .= "Subject with $clause $code is not exists\n";
            continue;
        }
        
        $subject  = $db->single('subjects',[''.$clause.'' => $code]);

        if(!$db->exists('bills',['bill_code' => $subject->code.'-'.$bill_code]))
        {
            try {
                $billCodeExplode = explode('-', $bill_code);
                $merchant = $db->single('merchants',['name' => strtoupper($billCodeExplode[0]) ]);
                if(!$merchant)
                {
                    $failed++;
                    $logs .= "Bill with $subject->code-$bill_code is not exists\n";
                    continue;
                }

                $data = [
                    'subject_id' => $subject->id,
                    'merchant_id' => $merchant->id,
                    'report_id' => $report_id,
                    'bill_code' => $subject->code.'-'.$bill_code,
                    'description' => 'Tagihan ' . $bill_code,
                    'amount' => $amount,
                    'remaining_payment' => $amount,
                    'status' => 'BELUM LUNAS',
                ];
        
                $insert = $db->insert('bills',$data);
        
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
            } catch (\Throwable $th) {
                //throw $th;
                $failed++;
                $logs .= "Bill with $subject->code-$bill_code is not exists\n";
                continue;
            }
            
        }

        if(!$db->exists('bills',['bill_code' => $subject->code.'-'.$bill_code, 'remaining_payment' => $amount]))
        {
            $failed++;
            $logs .= "Bill with $subject->code-$bill_code and amount $amount is not exists\n";
            continue;
        }

        $bill = $db->single('bills',['bill_code' => $subject->code.'-'.$bill_code, 'remaining_payment' => $amount]);
        // $sisa = $bill->remaining_payment - $amount;
        // if($sisa < 0){
        //     $failed++;
        //     $logs .= "Amount of bill with $subject->code-$bill_code is bigger than remaining payment\n";
        //     continue; // something wrong
        // }
        $db->update('bills',[
            'remaining_payment' => 0,
            'status' => 'LUNAS'
        ],[
            'id' => $bill->id
        ]);

        $transaction = $db->insert('transactions',[
            'subject_id' => $subject->id,
            'report_id'  => $report_id,
            'user_id'  => auth()->user->id,
            'transaction_code' => $transaction_code,
            'total' => $amount
        ]);

        $insert = $db->insert('transaction_items',[
            'transaction_id' => $transaction->id,
            'bill_id' => $bill->id,
            'amount' => $amount,
            'description' => $description,
            'merchant_id' => $bill->merchant_id
        ]);

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

        $success++;
    }

    $db->insert('logs',[
        'name' => 'import transaction',
        'description' => $logs
    ]);

    set_flash_msg(['success'=>$success.' data berhasil di import.<br>'.$failed.' data gagal di import.']);
    header('location:'.routeTo('crud/index',['table'=>$table]));
}

return compact('table','error_msg','old','fields');
