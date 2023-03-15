<?php

$table = 'journals';
Page::set_title('Tambah '._ucwords(__($table)));
$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');
$fields = config('fields')[$table];


if(request() == 'POST')
{
    $conn = conn();
    $db   = new Database($conn);

    $report_id = activeMaster()->id;
    $account = $db->single('accounts',['code' => $_POST[$table]['account'], 'report_id' => $report_id]);
    if(empty($account))
    {
        set_flash_msg(['error'=>'Akun '.$_POST[$table]['account'].' tidak valid']);
        header('location:'.routeTo('journals/create'));
    }

    $debt =  $_POST[$table]['debit'];
    $credit =  $_POST[$table]['kredit'];
    $transaction_type = !empty($debt) ? 'Debit' : 'Kredit';

    $data = [
        'report_id' => $report_id,
        'account_id' => $account->id,
        'transaction_type' => $transaction_type,
        'amount' => !empty($debt) ? $debt : $credit,
        'description' => $_POST[$table]['description'],
        'date' => $_POST[$table]['date'],
    ];

    $insert = $db->insert($table,$data);

    set_flash_msg(['success'=>_ucwords(__($table)).' berhasil ditambahkan']);
    header('location:'.routeTo('journals/index'));
}

return compact('table','error_msg','old','fields');
