<?php
$conn = conn();
$db   = new Database($conn);

$report_id = activeMaster() ? activeMaster()->id : 0;
$merchants = $db->all('merchants',[
    'report_id' => $report_id
]);

$groups = $db->all('groups',[
    'report_id' => $report_id
]);

if(isset($_GET['draw']))
{
    $draw     = $_GET['draw'];
    $start    = $_GET['start'];
    $length   = $_GET['length'];
    $search   = $_GET['search']['value'];
    $order    = $_GET['order'];
    // $group    = $_GET['group'];
    $merchant = $_GET['merchant'];
    // $startAt  = $_GET['start_at'];
    // $endAt    = $_GET['end_at'];
    // $parentGroup    = $_GET['parent_group'];

    $report_id = activeMaster() ? activeMaster()->id : 0;

    $db->query = "SELECT * FROM `groups` WHERE parent_id IS NOT NULL AND report_id = $report_id";
    $groups = $db->exec('all');

    $parentGroups = $db->all('groups',[
        'report_id' => $report_id,
        'parent_id' => ['IS', 'NULL']
    ]);

    $where      = "";
    $additional = "";
    $group      = "bills.merchant_id, groups.id";
    $_where     = ["bills.merchant_id = $merchant"];
    if(isset($_GET['parent_group']) || isset($_GET['group']) || (isset($_GET['start_at']) && isset($_GET['end_at'])))
    {
        $_additional = [];
        // $where = "WHERE ";
        if(isset($_GET['parent_group']) && !empty($_GET['parent_group']) && empty($_GET['group']))
        {
            $parentGroup = $_GET['parent_group'] == 'active-group' ? ' IN ('.implode(',', config('activeGroups')).')' : ' = '.$_GET['parent_group'];
            $_where[] = " (SELECT parent_id FROM `groups` WHERE id=(SELECT group_id FROM subject_groups WHERE user_id=subjects.user_id)) $parentGroup";
        }

        if(isset($_GET['group']) && !empty($_GET['group']))
        {
            $_where[] = " (SELECT id FROM `groups` WHERE id=(SELECT group_id FROM subject_groups WHERE user_id=subjects.user_id)) = $_GET[group]";
        }

        if(isset($_GET['start_at']) && !empty(($_GET['start_at'])) && isset($_GET['end_at']) && !empty(($_GET['end_at'])))
        {
            $_where[] = " (bills.created_at >= '$_GET[start_at] 00:00:00' AND bills.created_at <= '$_GET[end_at] 23:59:59')";
        }

        $where = count($_where) ? "WHERE ".implode(" AND ", $_where) : "";
        $additional = count($_additional) ? ",".implode(",", $_additional) : "";
    }
        
    $query = "SELECT 
            bills.merchant_id,
            groups.name group_name,
            merchants.name merchant_name,
            SUM(bills.amount) bills_total_amount,
            SUM(bills.amount-bills.remaining_payment) bills_total_payment,
            SUM(bills.remaining_payment) bills_total_remaining_payment
            $additional
          FROM 
            bills
          INNER JOIN subjects ON subjects.id=bills.subject_id
          INNER JOIN subject_groups ON subject_groups.user_id=subjects.user_id
          INNER JOIN `groups` ON groups.id=subject_groups.group_id
          INNER JOIN merchants ON merchants.id=bills.merchant_id
          $where
          GROUP BY $group";

    $db->query = $query . " LIMIT $start,$length";
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