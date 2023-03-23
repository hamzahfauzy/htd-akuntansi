<?php

$conn = conn();
$db   = new Database($conn);

$db->query = "SELECT bills.*, CONCAT(bills.bill_code,' - ',merchants.name) as bill_name, merchants.name, merchants.id as merchant_id FROM bills JOIN merchants ON merchants.id=bills.merchant_id WHERE bills.status='BELUM LUNAS'";
$data  = $db->exec('all');

echo json_encode([
    'success' => true,
    'data' => $data,
    'message' => 'unpayment bill retrieve'
]);
die();