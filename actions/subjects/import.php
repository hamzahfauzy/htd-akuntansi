<?php

$table = 'subjects';
Page::set_title('Import '._ucwords(__($table)));
$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');
$fields = [
    'file' => [
        'label' => 'file',
        'type'  => 'file'
    ],
    'role' => [
        'label' => 'Role',
        'type'  => 'options-obj:roles,id,name'
    ],
];

if(request() == 'POST')
{
    Validation::run([
        'file' => [
            'file','required','mime:xls,xlsx'
        ],
        'role' => [
            'required'
        ]
    ], array_merge($_POST['subjects'],$_FILES));

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
    $failed_code = [];
    $report_id = activeMaster()->id;

    $conn = conn();
    $db   = new Database($conn);

    for ($row = 2; $row <= $highestRow; $row++) { 
        $code = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
        $code = trim($code);
        // check if code exists
        if($db->exists('subjects',['code' => $code ]))
        {
            $failed_code[] = $code;
            $failed++;
            continue;
        }

        $data = [
            'code' => $code,
            'name' => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
            'address' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
            'phone' => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
            'email' => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
            'group' => $worksheet->getCellByColumnAndRow(6, $row)->getValue()
        ];

        if(empty($data['phone']))
        {
            unset($data['phone']);
        }
        
        if(empty($data['email']))
        {
            unset($data['email']);
        }
        
        if(empty($data['group']))
        {
            unset($data['group']);
        }

        $user = $db->insert('users',[
            'name' => $data['name'],
            'username' => $data['code'],
            'password' => md5($data['code']),
        ]);

        $data['user_id'] = $user->id;
        $subject = $db->insert($table, array_filter($data, function($key){ return $key != 'group'; }, ARRAY_FILTER_USE_KEY));
        $db->insert('user_roles',[
            'user_id' => $user->id,
            'role_id' => $_POST[$table]['role']
        ]);

        if(isset($data['group']) && $db->exists('groups',['name'=>$data['group']]))
        {
            $group = $db->single('groups',['name' => $data['group']]);
            $db->insert('subject_groups',[
                'user_id' => $user->id,
                'group_id'   => $group->id,
                'report_id'  => $report_id
            ]);
        }
        $success++;
    }

    set_flash_msg(['success'=>$success.' data berhasil di import.<br>'.$failed.' data gagal di import. ('.implode(',',$failed_code).')']);
    header('location:'.routeTo('crud/index',['table'=>'subjects']));
}

return compact('table','error_msg','old','fields');
