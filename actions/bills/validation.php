<?php


$inputFileName = 'data-spp.xlsx';

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

$data = [];
$rows = "";

for ($row = 2; $row <= $highestRow; $row++) { 
    $code = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
    $code = trim($code);

    if(empty($code) || is_null($code)) continue;
    $amount = $worksheet->getCellByColumnAndRow(5, $row)->getValue();

    $bill = $db->single('bills', [
        'bill_code' => $code
    ]);

    if($bill)
    {
        // $data[] = $bill;
        $rows .= "<tr><td>$code</td><td>$amount</td><td>$bill->status</td></tr>";
    }
    else
    {
        $rows .= "<tr><td>$code</td><td>$amount</td><td>NOT EXISTS IN DATABASE</td></tr>";
        // $data[] = [
        //     'bill_code' => $code,
        //     'amount' => $amount,
        //     'description' => '',
        //     'status' => 'NOT EXISTS IN DATABASE'
        // ];
    }
}

echo "<table border='1' cellpadding='5' cellspacing='0'>$rows</table>";
die();