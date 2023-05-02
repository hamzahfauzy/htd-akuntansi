<?php

$conn = conn();
$db   = new Database($conn);

$params = [
    'report_id' => (activeMaster()?activeMaster()->id:0)
];

if(isset($_GET['group_name']))
{
    $params['name'] = $_GET['group_name'];
}

$groups = $db->all('groups', $params);

$groups = array_map(function($g) use ($db){
    $db->query = "SELECT bills.subject_id, subjects.code subject_code, subjects.name subject_name, subjects.email subject_email, subjects.address subject_address FROM bills JOIN subjects ON subjects.id=bills.subject_id WHERE bills.status='BELUM LUNAS'";
    $subjects = $db->exec('all');

    $subjects = array_map(function($s) use ($db){
        $db->query = "SELECT bills.remaining_payment bill_amount, bills.bill_code, merchants.name merchant_name FROM bills JOIN merchants ON merchants.id=bills.merchant_id WHERE bills.subject_id=$s->subject_id AND bills.status='BELUM LUNAS'";
        $s->bills  = $db->exec('all');
    
        return $s;
    }, $subjects);

    $g->subjects = $subjects;
    return $g;

}, $groups);

echo json_encode([
    'success' => true,
    'data' => $groups,
    'message' => 'unpayment bill retrieve'
]);
die();