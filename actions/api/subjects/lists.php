<?php
$conn = conn();
$db   = new Database($conn);

$report_id = activeMaster()?activeMaster()->id:0;

$where = "WHERE user_id IN (SELECT user_id FROM subject_groups WHERE report_id = $report_id)";
if(isset($_GET['keyword']))
{
    $keyword = $_GET['keyword'];
    $where .= " AND (subjects.name LIKE '%$keyword%' OR subjects.code LIKE '%$keyword%')";
}
$db->query = "SELECT *, CONCAT(subjects.code,' - ',subjects.name) as subject_name, (SELECT groups.name FROM `groups` WHERE groups.id = (SELECT group_id FROM subject_groups WHERE user_id = subjects.user_id AND report_id = $report_id)) as group_name FROM subjects $where ORDER BY id";
$data  = $db->exec('all');

echo json_encode($data);
die();