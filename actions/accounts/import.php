<?php

$table = 'accounts';
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
        $code = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
        // check if code exists
        if($db->exists('accounts',['code' => $code, 'report_id' => $report_id]))
        {
            $failed++;
            continue;
        }

        $parent = null;
        $parent_code = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
        if(!empty($parent_code))
        {
            $_parent = $db->single('accounts',['code' => $parent_code, 'report_id'=>$report_id]);
            if(!$_parent)
            {
                $failed++;
                continue;
            }
            $parent = $_parent->id;
        }
        $data = [
            'report_id' => $report_id,
            'parent_id' => $parent,
            'code' => $code,
            'name' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
            'balance_position' => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
            'report_position' => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
            'balance_amount' => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),
            'budget_amount' => $worksheet->getCellByColumnAndRow(7, $row)->getValue(),
        ];
        
        if(is_null($parent))
        {
            unset($data['parent_id']);
        }

        $db->insert('accounts', $data);
        $success++;
    }

    set_flash_msg(['success'=>$success.' data berhasil di import.<br>'.$failed.' data gagal di import.']);
    header('location:'.routeTo('accounts/index'));
}

return compact('table','error_msg','old','fields');
