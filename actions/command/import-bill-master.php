<?php

$table = 'transactions';

$inputFileName = 'public/master-data-tagihan.xlsx';

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
    $amount = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
    $merchant = $worksheet->getCellByColumnAndRow(5, $row)->getValue();

    // check if subject exists
    if(!$db->exists('subjects',['email' => $code]))
    {
        $failed++;
        $logs .= "Subject with email $code is not exists\n";
        continue;
    }
    
    $subject  = $db->single('subjects',['email' => $code]);
    $merchant = $db->single('merchants',['name' => $merchant]);

    $num_of_bills = 12;
    $total_amount = $amount * $num_of_bills;

    // jurnal debet
    $db->insert('bill_master',[
        'merchant_id' => $merchant->id,
        'subject_id' => $subject->id,
        'base_amount' => $amount,
        'total_amount' => $total_amount,
        'num_of_bills' => $num_of_bills,
        'start_at' => '2023-07-01',
        'end_at' => '2024-06-30',
    ]);

    $success++;
}

$db->insert('logs',[
    'name' => 'import master tagihan',
    'description' => $logs
]);