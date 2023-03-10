<?php

$table = 'cash_flows';
Page::set_title(_ucwords(__($table)));
$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');
$fields = config('fields')[$table];

if(file_exists('../actions/'.$table.'/override-index-fields.php'))
    $fields = require '../actions/'.$table.'/override-index-fields.php';

$data = $db->all('cash_flows',[
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

return [
    'table' => $table,
    'success_msg' => $success_msg,
    'fields' => $fields,
    'data' => $data,
    'accounts' => $dump_accounts,
];
