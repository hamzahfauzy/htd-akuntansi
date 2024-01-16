<?php
Page::set_title('Laporan Total');
$conn = conn();
$db   = new Database($conn);
$success_msg = get_flash_msg('success');

$report_id = activeMaster() ? activeMaster()->id : 0;

$db->query = "SELECT * FROM `groups` WHERE parent_id IS NOT NULL AND report_id = $report_id";
$groups = $db->exec('all');

$parentGroups = $db->all('groups',[
    'report_id' => $report_id,
    'parent_id' => ['IS', 'NULL']
]);

$where      = "";
$additional = "";
$group      = "bills.merchant_id";
if(isset($_GET['parent_group']) || isset($_GET['group']) || (isset($_GET['start_at']) && isset($_GET['end_at'])))
{
    $_where = [];
    $_additional = [];
    // $where = "WHERE ";
    if(isset($_GET['parent_group']) && empty($_GET['group']))
    {
        $_additional[] = "(SELECT id FROM `groups` WHERE id=(SELECT group_id FROM subject_groups WHERE user_id=subjects.user_id)) group_id";
        $_where[] = " (SELECT parent_id FROM `groups` WHERE id=(SELECT group_id FROM subject_groups WHERE user_id=subjects.user_id)) = $_GET[parent_group]";
        $group   .= ", group_id";
    }

    if(isset($_GET['group']) && !empty($_GET['group']))
    {
        $_additional[] = "(SELECT id FROM `groups` WHERE id=(SELECT group_id FROM subject_groups WHERE user_id=subjects.user_id)) group_id";
        $_where[] = " (SELECT id FROM `groups` WHERE id=(SELECT group_id FROM subject_groups WHERE user_id=subjects.user_id)) = $_GET[group]";
        $group   .= ", group_id";
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
            merchants.name merchant_name,
            SUM(bills.amount) bills_total_amount,
            SUM(bills.amount-bills.remaining_payment) bills_total_payment,
            SUM(bills.remaining_payment) bills_total_remaining_payment
            $additional
          FROM 
            bills
          INNER JOIN subjects ON subjects.id=bills.subject_id
          INNER JOIN merchants ON merchants.id=bills.merchant_id
          $where
          GROUP BY $group";
$db->query = $query;
$data = $db->exec('all');

return compact('data','groups','parentGroups','success_msg');