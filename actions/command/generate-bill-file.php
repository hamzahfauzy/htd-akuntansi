<?php


$inputFileName = 'public/spp-juli-2024.xlsx';

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

$worksheet->setCellValue([7,1], 'Kode Tagihan');

for ($row = 2; $row <= $highestRow; $row++) { 
    $code = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
    $clause = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
    $bill_code = $worksheet->getCellByColumnAndRow(3, $row)->getValue();

    // check if subject exists
    if(!$db->exists('subjects',[''.$clause.'' => $code]))
    {
        continue;
    }
    
    $subject  = $db->single('subjects',[''.$clause.'' => $code]);
    $val = $subject->code . '-' . $bill_code;
    $worksheet->setCellValue([7,$row], $val);   
}

$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save($inputFileName); // save to the same file