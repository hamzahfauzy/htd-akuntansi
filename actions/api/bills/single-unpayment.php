<?php

if(!isset($_GET['subject_code']))
{
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'subject_code required'
    ]);
    die();
}

Validation::run([
    'subject_code' => [
        'required','exists:subjects,code,'.$_GET['subject_code']
    ],
],$_GET,'json');

$conn = conn();
$db   = new Database($conn);

$db->query = "SELECT id, subjects.code subject_code, subjects.name subject_name, subjects.email subject_email, subjects.address subject_address FROM subjects WHERE code='$_GET[subject_code]'";
$subject = $db->exec('single');

$db->query = "SELECT bills.remaining_payment bill_amount, bills.bill_code, merchants.name merchant_name FROM bills JOIN merchants ON merchants.id=bills.merchant_id WHERE bills.subject_id=$subject->id AND bills.status='BELUM LUNAS'";
$subject->bills  = $db->exec('all');

echo json_encode([
    'success' => true,
    'data' => $subject,
    'message' => 'unpayment bill retrieve'
]);
die();