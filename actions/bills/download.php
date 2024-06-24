<?php

$table = 'bills';
$conn = conn();
$db   = new Database($conn);

$group = $_GET['group'];
$merchant = $_GET['merchant'];
$subject = $_GET['subject'];
$status = $_GET['status'];
$startAt = $_GET['start_at'];
$endAt = $_GET['end_at'];
$search = $_GET['search'];

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

if($startAt || $endAt)
{
    $startAt = $startAt ?? date('Y-m-d');
    $endAt = $endAt ?? date('Y-m-d');

    $where .= " AND DATE_FORMAT(bills.created_at, '%Y-%m-%d') BETWEEN '$startAt' AND '$endAt'";
}

$query = "SELECT `$table`.*, CONCAT(subjects.code,' - ',subjects.name) subject_name FROM `$table` JOIN subjects ON subjects.id = `$table`.subject_id $where";
$db->query = $query;
$data  = json_decode(json_encode($db->exec('all')), 1);

$filename = "bills-download.csv";
$delimiter = ";";
header( 'Content-Type: application/csv' );
header( 'Content-Disposition: attachment; filename="' . $filename . '";' );

// clean output buffer
ob_end_clean();

$handle = fopen( 'php://output', 'w' );

// use keys as column titles
fputcsv( $handle, array_keys( $data[0] ), $delimiter );

foreach ( $data as $value ) {
    fputcsv( $handle, $value, $delimiter );
}

fclose( $handle );

// use exit to get rid of unexpected output afterward
exit();