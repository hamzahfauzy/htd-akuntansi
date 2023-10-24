<?php

use Spipu\Html2Pdf\Html2Pdf;

$conn = conn();
$db   = new Database($conn);

$subject = $db->single('subjects', ['id' => $_GET['id']]);

$db->query = "SELECT bills.*, merchants.name as merchant_name FROM bills JOIN merchants ON merchants.id = bills.merchant_id  WHERE bills.subject_id = $subject->id AND bills.status = 'BELUM LUNAS'";
$subject->bills   = $db->exec('all');

$path = 'assets/img/main-logo.png';
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

$html = load_templates('subjects/bills-download', [
    'subject' => $subject,
    'base64'  => $base64
], true);

$html2pdf = new Html2Pdf();
$html2pdf->writeHTML($html);
$html2pdf->output('bill-download-'.$subject->code.'.pdf');
die;