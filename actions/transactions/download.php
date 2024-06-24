<?php

$table = 'transactions';
$conn = conn();
$db   = new Database($conn);

$startAt = $_GET['start_at'];
$endAt = $_GET['end_at'];

$where = "";

$where = empty($where) ? 'WHERE ' : $where . ' AND ';
$where .= ' report_id='.(activeMaster()?activeMaster()->id:0);

if($startAt || $endAt)
{
    $startAt = $startAt ?? date('Y-m-d');
    $endAt = $endAt ?? date('Y-m-d');

    $where .= " AND DATE_FORMAT(transactions.created_at, '%Y-%m-%d') BETWEEN '$startAt' AND '$endAt'";
}

$query = "SELECT $table.*, CONCAT(subjects.code,' - ',subjects.name) subject_name FROM $table JOIN subjects ON subjects.id = $table.subject_id $where";
$db->query = $query;
$data  = json_decode(json_encode($db->exec('all')), 1);

$filename = "transactions-download.csv";
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