<?php

$conn = conn();
$db   = new Database($conn);

$db->query = "SELECT bills.*, subjects.code subject_code, subjects.name subject_name, subjects.email subject_email, subjects.address subject_address, CONCAT(bills.bill_code,' - ',merchants.name) as bill_name, merchants.name, merchants.id as merchant_id FROM bills JOIN subjects ON subjects.id=bills.subject_id JOIN merchants ON merchants.id=bills.merchant_id WHERE bills.status='BELUM LUNAS'";
$data  = $db->exec('all');

echo json_encode([
    'success' => true,
    'data' => $data,
    'message' => 'unpayment bill retrieve'
]);
die();