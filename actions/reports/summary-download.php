<?php

$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$report_id = activeMaster() ? activeMaster()->id : 0;
$group = $_GET['group'];
$merchant = $_GET['merchant'];

$where = "bills.report_id=$report_id";

if($group)
{
    $db->query = "SELECT subject_groups.*, subjects.id subject_id FROM subject_groups JOIN subjects ON subjects.user_id=subject_groups.user_id WHERE subject_groups.group_id=$group";
    $subject_groups = $db->exec('all');
    $subject_ids = array_map(function($sg){
        return $sg->subject_id;
    }, $subject_groups);

    if($subject_ids)
    {
        $where .= " AND bills.subject_id IN (".implode(',',$subject_ids).")";
    }
}

if($merchant)
{
    $where .= " AND bills.merchant_id=$merchant";
}

$query = "SELECT 
                (SELECT name FROM `groups` WHERE id=(SELECT group_id FROM subject_groups WHERE user_id=subjects.user_id)) `Group`,
                merchants.name `Merchant`,
                bills.description `Deskripsi`,
                COALESCE(SUM(bills.amount),0) `Total Tagihan`,
                COALESCE(SUM(bills.amount-bills.remaining_payment),0) `Total Pembayaran`,
                COALESCE(SUM(bills.remaining_payment),0) `Sisa`
            FROM 
                bills
            INNER JOIN subjects ON subjects.id=bills.subject_id
            INNER JOIN merchants ON merchants.id=bills.merchant_id
            WHERE $where
            GROUP BY bills.merchant_id, `Group`, bills.description";

$db->query = $query . " ORDER BY bills.created_at";
$data  = json_decode(json_encode($db->exec('all')), 1);

$filename = "summary-reports.csv";
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