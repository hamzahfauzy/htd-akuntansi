<?php

$table = 'journals';
Page::set_title('Edit '._ucwords(__($table)));
$conn = conn();
$db   = new Database($conn);
$error_msg = get_flash_msg('error');
$old = get_flash_msg('old');
$fields = config('fields')[$table];

$db->query = "SELECT $table.*, accounts.code as account FROM $table JOIN accounts ON accounts.id = $table.account_id WHERE $table.id = $_GET[id]";
$data = $db->exec('single');

$data->debit = $data->transaction_type == 'Debit' ? number_format($data->amount) : '';
$data->kredit = $data->transaction_type == 'Kredit' ? number_format($d->amount) : '';

if(request() == 'POST')
{
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

    $edit = $db->update($table,$data,[
        'id' => $_GET['id']
    ]);

    set_flash_msg(['success'=>_ucwords(__($table)).' berhasil diupdate']);
    header('location:'.routeTo('journals/index'));
}

return [
    'data' => $data,
    'error_msg' => $error_msg,
    'old' => $old,
    'table' => $table,
    'fields' => $fields
];
