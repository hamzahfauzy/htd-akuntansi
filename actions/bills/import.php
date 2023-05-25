<?php

$table = 'bills';
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
        $bill_code = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
        $amount = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
        $merchant = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
        $description = $worksheet->getCellByColumnAndRow(6, $row)->getValue();

        // check if subject exists
        if(!$db->exists('subjects',[''.$clause.'' => $code]))
        {
            $failed++;
            $logs .= "Subject with $clause $code is not exists\n";
            continue;
        }
        
        if(!$db->exists('merchants',['name' => $merchant]))
        {
            $failed++;
            $logs .= "Merchant with name $merchant is not exists\n";
            continue;
        }

        $subject  = $db->single('subjects',[''.$clause.'' => $code]);
        $merchant = $db->single('merchants',['name' => $merchant]);

        $data = [
            'subject_id' => $subject->id,
            'merchant_id' => $merchant->id,
            'report_id' => $report_id,
            'bill_code' => $subject->code.'-'.$bill_code,
            'description' => $description,
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

        $success++;
    }

    $db->insert('logs',[
        'name' => 'import bill',
        'description' => $logs
    ]);

    set_flash_msg(['success'=>$success.' data berhasil di import.<br>'.$failed.' data gagal di import.']);
    header('location:'.routeTo('crud/index',['table'=>$table]));
}

return compact('table','error_msg','old','fields');
