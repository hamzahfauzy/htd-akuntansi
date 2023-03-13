<?php

$table = 'journals';
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
    ], $_FILES);

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
        $code = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
        // check if code exists
        if($db->exists('accounts',['code' => $code, 'report_id' => $report_id]))
        {
            $account = $db->single('accounts',['code' => $code, 'report_id' => $report_id]);
            $date = $worksheet->getCellByColumnAndRow(1, $row)->getFormattedValue();
            $date = DateTime::createFromFormat('d-m-Y', $date);
            $debt =  $worksheet->getCellByColumnAndRow(4, $row)->getValue();
            $credit =  $worksheet->getCellByColumnAndRow(5, $row)->getValue();
            $transaction_type = !empty($debt) ? 'Debit' : 'Kredit';
            $data = [
                'report_id' => $report_id,
                'account_id' => $account->id,
                'transaction_type' => $transaction_type,
                'amount' => !empty($debt) ? $debt : $credit,
                'description' => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),
                'date' => $date->format('Y-m-d'),
            ];

            $db->insert($table, $data);
            $success++;
        }
        else
        {
            $failed++;
        }
    }

    set_flash_msg(['success'=>$success.' data berhasil di import.<br>'.$failed.' data gagal di import.']);
    header('location:'.routeTo('journals/index'));
}

return compact('table','error_msg','old','fields');
