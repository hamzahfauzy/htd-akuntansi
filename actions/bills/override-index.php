<?php

$group = $_GET['searchByGroup'];
$merchant = $_GET['searchByMerchant'];
$subject = $_GET['searchBySubject'];
$status = $_GET['searchByStatus'];

if($search)
{
    $where = "WHERE (description LIKE '%$search%' OR bill_code LIKE '%$search%') ";
}

$where = empty($where) ? 'WHERE ' : $where . ' AND ';
$where .= ' report_id='.(activeMaster()?activeMaster()->id:0);

if($group && !$subject)
{
    $db->query = "SELECT subject_groups.*, subjects.id subject_id FROM subject_groups JOIN subjects ON subjects.user_id=subject_groups.user_id WHERE subject_groups.group_id=$group";
    $subject_groups = $db->exec('all');
    $subject_ids = array_map(function($sg){
        return $sg->subject_id;
    }, $subject_groups);

    if($subject_ids)
    {
        $where .= " AND subject_id IN (".implode(',',$subject_ids).")";
    }
}

if($subject && !$group)
{
    $where .= " AND subject_id=$subject";
}

if($merchant)
{
    $where .= " AND merchant_id=$merchant";
}

if($status)
{
    $where .= " AND status='$status'";
}

$query = "SELECT `$table`.*, CONCAT(subjects.code,' - ',subjects.name) subject_name FROM `$table` JOIN subjects ON subjects.id = `$table`.subject_id $where ORDER BY ".$col_order." ".$order[0]['dir'];
$db->query = $query ." LIMIT $start,$length";
$data  = $db->exec('all');

$db->query = $query;
$total = $db->exec('exists');

return compact('data','total');