<?php

$conn = conn();
$db   = new Database($conn);
$report_id = activeMaster() ? activeMaster()->id : 0;

$where = "WHERE report_id=$report_id AND parent_id IS NOT NULL";

if(isset($_GET['keyword']))
{
    $keyword = $_GET['keyword'];
    $where .= " AND (name LIKE '%$keyword%' OR code LIKE '%$keyword%')";
}

$data = $db->all('accounts', $where,[
    'code' => 'ASC'
]);

echo json_encode([
    'success' => true,
    'data' => $data,
    'message' => 'active accounts retrieved'
]);
die();