<?php

Validation::run([
    'subject_id' => [
        'required','exists:subjects,id,'.$_GET['subject_id']
    ],
],$_GET,'json');

$conn = conn();
$db   = new Database($conn);

$report_id = activeMaster()?activeMaster()->id:0;
$subject = $db->single('subjects',['id'=> $_GET['subject_id']]);

$where = 'WHERE bills.status="BELUM LUNAS" AND bills.report_id='.(activeMaster()?activeMaster()->id:0).' AND bills.subject_id='.$subject->id.'';
if(isset($_GET['keyword']))
{
    $keyword = $_GET['keyword'];
    $where .= " AND (bills.bill_code LIKE '%$keyword%' OR merchants.name LIKE '%$keyword%')";
}

$db->query = "SELECT bills.*, CONCAT(bills.bill_code,' - ',merchants.name) as bill_name, merchants.name, merchants.id as merchant_id FROM bills JOIN merchants ON merchants.id=bills.merchant_id $where";
$data  = $db->exec('all');

echo json_encode($data);
die();