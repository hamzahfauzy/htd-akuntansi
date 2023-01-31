<?php

$table = 'accounts';
Page::set_title(_ucwords(__($table)));
$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');
$fields = config('fields')[$table];

if(file_exists('../actions/'.$table.'/override-index-fields.php'))
    $fields = require '../actions/'.$table.'/override-index-fields.php';

$report_id = activeMaster() ? activeMaster()->id : 0;
$params = [
    'report_id' => $report_id,
    'parent_id' => ['IS','NULL']
];
$parent = null;
if(isset($_GET['parent_id']) && !empty($_GET['parent_id']))
{
    $params['parent_id'] = $_GET['parent_id'];
    $parent = $db->single('accounts',['id' => $params['parent_id']]);
}

if(isset($_GET['full']))
{
    unset($params['parent_id']);
}

$data = $db->all('accounts', $params,[
    'code' => 'ASC'
]);

return [
    'table' => $table,
    'success_msg' => $success_msg,
    'fields' => $fields,
    'data' => $data,
    'parent' => $parent,
];
