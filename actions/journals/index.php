<?php

$table = 'journals';
Page::set_title(_ucwords(__($table)));
$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');
$fields = config('fields')[$table];

$data = $db->all($table,[
    'report_id' => activeMaster()?activeMaster()->id:0
],[
    'date' => 'ASC'
]);

$account_ids = array_column((array) $data, 'account_id');
$params = $account_ids ? ['id' => ['IN','('.implode(',',$account_ids).')']] : [];
$accounts = $db->all('accounts',$params);
$dump_accounts = [];
foreach($accounts as $key => $account)
{
    $dump_accounts[$account->id] = $account;
}

$data = array_map(function($d) use ($dump_accounts){
    $d->debit = $d->transaction_type == 'Debit' ? number_format($d->amount) : '';
    $d->kredit = $d->transaction_type == 'Kredit' ? number_format($d->amount) : '';
    $d->account = $dump_accounts[$d->account_id]->code .' '. $dump_accounts[$d->account_id]->name;
    return $d;
}, $data);

return [
    'table' => $table,
    'success_msg' => $success_msg,
    'fields' => $fields,
    'data' => $data,
    'accounts' => $dump_accounts,
];
