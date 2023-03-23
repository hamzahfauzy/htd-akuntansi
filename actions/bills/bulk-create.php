<?php

$table = 'bills';
Page::set_title('Import '._ucwords(__($table)));
$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');
$fields = [
    'file' => [
        'label' => 'file',
        'type'  => 'file'
    ],
    'merchant' => [
        'label' => 'Merchant',
        'type'  => 'options-obj:merchants,id,name'
    ]
];

return compact('fields','error_msg','old');