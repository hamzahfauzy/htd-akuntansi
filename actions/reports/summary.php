<?php
Page::set_title('Laporan Rekapitulasi');
$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$report_id = activeMaster() ? activeMaster()->id : 0;
$merchants = $db->all('merchants',[
    'report_id' => $report_id
]);

$groups = $db->all('groups',[
    'report_id' => $report_id
]);

if(isset($_GET['draw']))
{
    $draw    = $_GET['draw'];
    $start   = $_GET['start'];
    $length  = $_GET['length'];
    $search  = $_GET['search']['value'];
    $order   = $_GET['order'];
    $group = $_GET['searchByGroup'];
    $merchant = $_GET['searchByMerchant'];

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

    if(!empty($search))
    {
        $where .= " AND (subjects.name LIKE '%$search%' OR 
                         subjects.code LIKE '%$search%' OR 
                         merchants.name LIKE '%$search%')";
    }

    $columns = [
        'subject_name',
        'group_name',
        'merchant_name',
        'bills_total_amount',
        'bills_total_payment',
        'bills_total_remaining_payment',
    ];

    $col_order = $order[0]['column']-1;
    $col_order = $col_order < 0 ? 'bills.id' : $columns[$col_order];
    $col_order = $col_order ." ".$order[0]['dir'];
    
    $query = "SELECT 
                    bills.*, 
                    SUM(bills.amount) bills_total_amount,
                    SUM(bills.amount-bills.remaining_payment) bills_total_payment,
                    SUM(bills.remaining_payment) bills_total_remaining_payment,
                    merchants.name merchant_name,
                    (SELECT name FROM groups WHERE id=(SELECT group_id FROM subject_groups WHERE user_id=subjects.user_id)) group_name
                FROM 
                    bills
                INNER JOIN subjects ON subjects.id=bills.subject_id
                INNER JOIN merchants ON merchants.id=bills.merchant_id
                WHERE $where
                GROUP BY bills.merchant_id, group_name";

    $db->query = $query . " ORDER BY $col_order LIMIT $start,$length";
    $data  = $db->exec('all');

    $db->query = $query;
    $total = $db->exec('exists');

    $results = [];

    foreach($data as $key => $d)
    {
        $no = $key+($start+1);
        $results[$key] = [
            $no,
            $d->group_name,
            $d->merchant_name,
            number_format($d->bills_total_amount),
            number_format($d->bills_total_payment),
            number_format($d->bills_total_remaining_payment),
        ];
    }

    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => (int)$total,
        "recordsFiltered" => (int)$total,
        "data" => $results
    ]);

    die();
}

return compact('merchants','groups','success_msg');