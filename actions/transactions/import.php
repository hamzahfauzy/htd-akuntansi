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

    for ($row = 2; $row <= $highestRow; $row++) { 
        $code = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
        $transaction_code = 'TRX-'.strtotime('now');
        $amount = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
        $bill_code = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
        $description = $worksheet->getCellByColumnAndRow(5, $row)->getValue();

        // check if subject exists
        if(!$db->exists('subjects',['code' => $code]))
        {
            $failed++;
            continue;
        }
        
        $subject  = $db->single('subjects',['code' => $code]);

        if(!$db->exists('bills',['bill_code' => $subject->code.'-'.$bill_code]))
        {
            $failed++;
            continue;
        }

        $bill = $db->single('bills',['bill_code' => $subject->code.'-'.$bill_code]);

        $transaction = $db->insert('transactions',[
            'subject_id' => $subject->id,
            'report_id'  => $report_id,
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

    set_flash_msg(['success'=>$success.' data berhasil di import.<br>'.$failed.' data gagal di import.']);
    header('location:'.routeTo('crud/index',['table'=>$table]));
}

return compact('table','error_msg','old','fields');
